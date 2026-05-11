<?php
require_once __DIR__ . '/../core/Controller.php';

class AppointmentController extends Controller {
    private $appointmentModel;
    private $patientModel;
    private $userModel;
    private $animalBiteModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->appointmentModel = $this->model('Appointment');
        $this->patientModel = $this->model('Patient');
        $this->userModel = $this->model('User');
        $this->animalBiteModel = $this->model('AnimalBite');
    }
    
    public function index() {
        // Redirect patients to their personal appointments page
        if ($_SESSION['role'] === ROLE_PATIENT) {
            $this->redirect('my-appointments');
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $userRole = $_SESSION['role'];
        $filterDate = isset($_GET['date']) ? $_GET['date'] : ($userRole === ROLE_PATIENT ? '' : date('Y-m-d'));
        $filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
        $patientId = 0;

        if ($userRole === ROLE_PATIENT) {
            $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
            $patientId = $patient ? $patient['id'] : 0;
        } else {
            $patientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
        }

        $appointments = $this->appointmentModel->getAppointmentsWithFilters($filterDate, $filterStatus, $limit, $offset, $patientId);
        $totalAppointments = $this->appointmentModel->countWithFilters($filterDate, $filterStatus, $patientId);
        $totalPages = ceil($totalAppointments / $limit);

        // Calculate statistics for data cards
        $todayAppointments = $this->appointmentModel->countWithFilters(date('Y-m-d'), '', 0);
        $confirmedAppointments = $this->appointmentModel->countWithFilters('', 'confirmed', 0);
        $pendingAppointments = $this->appointmentModel->countWithFilters('', 'scheduled', 0);
        $cancelledAppointments = $this->appointmentModel->countWithFilters('', 'cancelled', 0);

        $this->view('appointments/index', [
            'appointments' => $appointments,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalAppointments' => $totalAppointments,
            'filterDate' => $filterDate,
            'filterStatus' => $filterStatus,
            'patientId' => $patientId,
            'userRole' => $userRole,
            'todayAppointments' => $todayAppointments,
            'confirmedAppointments' => $confirmedAppointments,
            'pendingAppointments' => $pendingAppointments,
            'cancelledAppointments' => $cancelledAppointments
        ]);
    }
    
    public function create() {
        $userRole = $_SESSION['role'];
        $appointmentType = isset($_GET['type']) ? $_GET['type'] : 'vaccination';
        $patientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
        $userPatient = null;

        if ($userRole === ROLE_PATIENT) {
            $userPatient = $this->patientModel->findByUserId($_SESSION['user_id']);
            if ($userPatient) {
                $patientId = $userPatient['id'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patient_id' => $userPatient ? $userPatient['id'] : (int)$_POST['patient_id'],
                'staff_id' => $userRole === ROLE_PATIENT ? null : (!empty($_POST['staff_id']) ? (int)$_POST['staff_id'] : null),
                'appointment_date' => $_POST['appointment_date'],
                'appointment_time' => $_POST['appointment_time'],
                'appointment_type' => $userRole === ROLE_PATIENT ? 'vaccination' : $_POST['appointment_type'],
                'status' => $userRole === ROLE_PATIENT ? 'scheduled' : 'confirmed',
                'notes' => $this->sanitize($_POST['notes'])
            ];

            // Bite data
            $biteData = [
                'patient_id' => $userPatient ? $userPatient['id'] : (int)$_POST['patient_id'],
                'bite_date' => $_POST['bite_date'],
                'bite_time' => $_POST['bite_time'],
                'animal_type' => $_POST['animal_type'],
                'animal_status' => $_POST['animal_status'],
                'bite_location' => $_POST['body_part'],
                'bite_type' => $_POST['bite_type'],
                'body_part' => $_POST['body_part'],
                'washing_done' => isset($_POST['washing_done']) ? (int)$_POST['washing_done'] : 0,
                'animal_description' => $this->sanitize($_POST['animal_description'] ?? '')
            ];

            $errors = array_merge(
                $this->validateAppointmentData($data),
                $this->validateBiteData($biteData)
            );

            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                $this->view('appointments/create', ['patientId' => $patientId, 'userRole' => $userRole, 'appointmentType' => $appointmentType, 'userPatient' => $userPatient]);
                return;
            }

            if (!$this->appointmentModel->isTimeSlotAvailable($data['appointment_date'], $data['appointment_time'])) {
                $_SESSION['error'] = 'Selected time slot is already booked. Please choose a different time.';
                $_SESSION['form_data'] = $data;
                $this->view('appointments/create', ['patientId' => $patientId, 'userRole' => $userRole, 'appointmentType' => $appointmentType, 'userPatient' => $userPatient]);
                return;
            }

            // Create appointment first
            if ($this->appointmentModel->create($data)) {
                $appointmentId = $this->db->lastInsertId();
                
                // Create bite record
                if ($this->animalBiteModel->create($biteData)) {
                    $biteId = $this->db->lastInsertId();
                    
                    // If admin created appointment with confirmed status, create first vaccination dose
                    $vaccinationCreated = false;
                    if ($userRole !== ROLE_PATIENT && $data['status'] === 'confirmed') {
                        $this->vaccinationModel = $this->model('Vaccination');
                        $vaccinationId = $this->vaccinationModel->createFirstDoseFromAppointment($appointmentId);
                        
                        if ($vaccinationId) {
                            $vaccinationCreated = true;
                            // Log vaccination creation
                            $this->userModel->logActivity($_SESSION['user_id'], 'auto_create_vaccination', 'vaccinations', $vaccinationId, null, 'Auto-created from confirmed appointment');
                        }
                    }
                    
                    // Log both appointment and bite creation
                    $this->userModel->logActivity($_SESSION['user_id'], 'create_appointment', 'appointments', $appointmentId, null, json_encode($data));
                    $this->userModel->logActivity($_SESSION['user_id'], 'create_bite_record', 'animal_bites', $biteId, null, json_encode($biteData));

                    $successMessage = $userRole === ROLE_PATIENT ? 'Your vaccination appointment request has been submitted. The admin will approve it shortly.' : 'Appointment scheduled successfully!';
                    if ($vaccinationCreated) {
                        $successMessage .= ' First vaccination dose has been automatically scheduled.';
                    }
                    
                    $_SESSION['success'] = $successMessage;
                    $redirectUrl = $userRole === ROLE_PATIENT ? 'my-appointments' : 'appointments';
                    $this->redirect($redirectUrl);
                } else {
                    // Rollback appointment creation if bite record fails
                    $this->appointmentModel->delete($appointmentId);
                    $_SESSION['error'] = 'Failed to save bite details. Please try again.';
                    $this->view('appointments/create', ['patientId' => $patientId, 'userRole' => $userRole, 'appointmentType' => $appointmentType, 'userPatient' => $userPatient]);
                    return;
                }
            } else {
                $_SESSION['error'] = 'Failed to schedule appointment. Please try again.';
                $this->view('appointments/create', ['patientId' => $patientId, 'userRole' => $userRole, 'appointmentType' => $appointmentType, 'userPatient' => $userPatient]);
            }
        } else {
            $this->view('appointments/create', ['patientId' => $patientId, 'userRole' => $userRole, 'appointmentType' => $appointmentType, 'userPatient' => $userPatient]);
        }
    }
    
    public function edit() {
        $userRole = $_SESSION['role'];
        
        // Only admin/staff can edit appointments - patients cannot edit
        if ($userRole === ROLE_PATIENT) {
            $_SESSION['error'] = 'You do not have permission to edit appointments. Please contact the administrator.';
            $this->redirect('my-appointments');
        }
        
        $appointmentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$appointmentId) {
            $_SESSION['error'] = 'Appointment ID is required.';
            $this->redirect('appointments');
        }
        
        $appointment = $this->appointmentModel->findById($appointmentId);
        
        if (!$appointment) {
            $_SESSION['error'] = 'Appointment not found.';
            $this->redirect('appointments');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patient_id' => (int)$_POST['patient_id'],
                'staff_id' => !empty($_POST['staff_id']) ? (int)$_POST['staff_id'] : null,
                'appointment_date' => $_POST['appointment_date'],
                'appointment_time' => $_POST['appointment_time'],
                'appointment_type' => $_POST['appointment_type'],
                'status' => $_POST['status'],
                'notes' => $this->sanitize($_POST['notes'])
            ];
            
            $errors = $this->validateAppointmentData($data, true);
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                $this->view('appointments/edit', ['appointment' => $appointment]);
                return;
            }
            
            $oldData = $appointment;
            
            if ($this->appointmentModel->update($appointmentId, $data)) {
                // Log activity
                $this->userModel->logActivity($_SESSION['user_id'], 'update_appointment', 'appointments', $appointmentId, json_encode($oldData), json_encode($data));
                
                $_SESSION['success'] = 'Appointment updated successfully!';
                $redirectUrl = $userRole === ROLE_PATIENT ? 'my-appointments' : 'appointments';
                $this->redirect($redirectUrl);
            } else {
                $_SESSION['error'] = 'Failed to update appointment. Please try again.';
                $this->view('appointments/edit', ['appointment' => $appointment]);
            }
        } else {
            $this->view('appointments/edit', ['appointment' => $appointment]);
        }
    }
    
    public function calendar() {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        
        $appointments = $this->appointmentModel->getAppointmentsByMonth($year, $month);
        $calendarData = $this->generateCalendarData($year, $month, $appointments);
        
        $this->view('appointments/calendar', [
            'calendarData' => $calendarData,
            'currentMonth' => $month,
            'currentYear' => $year,
            'appointments' => $appointments
        ]);
    }
    
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userRole = $_SESSION['role'];
            
            // Only patients cannot update appointment status
            if ($userRole === ROLE_PATIENT) {
                echo json_encode(['success' => false, 'message' => 'You do not have permission to update appointment status.']);
                return;
            }
            
            $appointmentId = (int)$_POST['appointment_id'];
            $status = $_POST['status'];
            
            $appointment = $this->appointmentModel->findById($appointmentId);
            
            if (!$appointment) {
                echo json_encode(['success' => false, 'message' => 'Appointment not found']);
                return;
            }
            
            if ($status === 'confirmed' && $userRole !== ROLE_ADMIN) {
                echo json_encode(['success' => false, 'message' => 'Only admin users can approve appointments.']);
                return;
            }
            
            $oldStatus = $appointment['status'];
            
            if ($this->appointmentModel->updateStatus($appointmentId, $status)) {
                // If appointment is confirmed, automatically create first vaccination dose
                $vaccinationCreated = false;
                $vaccinationMessage = '';
                
                if ($status === 'confirmed' && $oldStatus !== 'confirmed') {
                    // Load vaccination model
                    $this->vaccinationModel = $this->model('Vaccination');
                    
                    $vaccinationId = $this->vaccinationModel->createFirstDoseFromAppointment($appointmentId);
                    
                    if ($vaccinationId) {
                        $vaccinationCreated = true;
                        $vaccinationMessage = ' First vaccination dose has been automatically scheduled.';
                        
                        // Log vaccination creation
                        $this->userModel->logActivity($_SESSION['user_id'], 'auto_create_vaccination', 'vaccinations', $vaccinationId, null, 'Auto-created from appointment confirmation');
                    }
                }
                
                // Log activity
                $this->userModel->logActivity($_SESSION['user_id'], 'update_appointment_status', 'appointments', $appointmentId, $oldStatus, $status);
                
                $message = 'Appointment status updated successfully.' . $vaccinationMessage;
                echo json_encode(['success' => true, 'message' => $message, 'vaccination_created' => $vaccinationCreated]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update appointment status']);
            }
        }
    }
    
    public function myAppointments() {
        if ($_SESSION['role'] !== ROLE_PATIENT) {
            $_SESSION['error'] = 'Access denied. This page is for patients only.';
            $this->redirect('dashboard');
        }
        
        $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
        if (!$patient) {
            $_SESSION['error'] = 'Patient profile not found.';
            $this->redirect('dashboard');
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $appointments = $this->appointmentModel->getAppointmentsWithFilters('', '', $limit, $offset, $patient['id']);
        $totalAppointments = $this->appointmentModel->countWithFilters('', '', $patient['id']);
        $totalPages = ceil($totalAppointments / $limit);
        
        $this->view('appointments/my_appointments', [
            'appointments' => $appointments,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalAppointments' => $totalAppointments,
            'patient' => $patient
        ]);
    }
    
    public function getAvailableSlots() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $date = $_GET['date'];
            
            if (!$date) {
                echo json_encode(['success' => false, 'message' => 'Date is required']);
                return;
            }
            
            $availableSlots = $this->appointmentModel->getAvailableSlots($date);
            echo json_encode(['success' => true, 'slots' => $availableSlots]);
        }
    }
    
    private function validateAppointmentData($data, $isUpdate = false) {
        $errors = [];
        
        if (empty($data['patient_id'])) {
            $errors[] = 'Patient is required.';
        } else {
            $patient = $this->patientModel->findById($data['patient_id']);
            if (!$patient) {
                $errors[] = 'Selected patient does not exist.';
            }
        }
        
        if (empty($data['appointment_date'])) {
            $errors[] = 'Appointment date is required.';
        } elseif (!strtotime($data['appointment_date'])) {
            $errors[] = 'Invalid appointment date.';
        } elseif (strtotime($data['appointment_date']) < strtotime(date('Y-m-d'))) {
            $errors[] = 'Appointment date cannot be in the past.';
        }
        
        if (empty($data['appointment_time'])) {
            $errors[] = 'Appointment time is required.';
        }
        
        if (empty($data['appointment_type'])) {
            $errors[] = 'Appointment type is required.';
        }
        
        if ($isUpdate && empty($data['status'])) {
            $errors[] = 'Status is required.';
        }
        
        return $errors;
    }
    
    private function validateBiteData($data) {
        $errors = [];
        
        if (empty($data['patient_id'])) {
            $errors[] = 'Patient is required for bite record.';
        }
        
        if (empty($data['bite_date'])) {
            $errors[] = 'Bite date is required.';
        } elseif (!strtotime($data['bite_date'])) {
            $errors[] = 'Invalid bite date.';
        } elseif (strtotime($data['bite_date']) > strtotime(date('Y-m-d'))) {
            $errors[] = 'Bite date cannot be in the future.';
        }
        
        if (empty($data['bite_time'])) {
            $errors[] = 'Bite time is required.';
        }
        
        if (empty($data['animal_type'])) {
            $errors[] = 'Animal type is required.';
        } elseif (!in_array($data['animal_type'], ['dog', 'cat', 'rat', 'other'])) {
            $errors[] = 'Invalid animal type.';
        }
        
        if (empty($data['animal_status'])) {
            $errors[] = 'Animal status is required.';
        } elseif (!in_array($data['animal_status'], ['stray', 'owned', 'unknown'])) {
            $errors[] = 'Invalid animal status.';
        }
        
        if (empty($data['body_part'])) {
            $errors[] = 'Body part is required.';
        }
        
        if (empty($data['bite_type'])) {
            $errors[] = 'Bite type is required.';
        } elseif (!in_array($data['bite_type'], ['scratch', 'lick', 'bite', 'other'])) {
            $errors[] = 'Invalid bite type.';
        }
        
                
        return $errors;
    }
    
    private function generateCalendarData($year, $month, $appointments) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDay = date('N', mktime(0, 0, 0, $month, 1, $year));
        $calendar = [];
        
        // Group appointments by date
        $appointmentsByDate = [];
        foreach ($appointments as $appointment) {
            $date = date('Y-m-d', strtotime($appointment['appointment_date']));
            if (!isset($appointmentsByDate[$date])) {
                $appointmentsByDate[$date] = [];
            }
            $appointmentsByDate[$date][] = $appointment;
        }
        
        // Build calendar weeks
        $week = [];
        $dayCount = 1;
        
        // Add empty cells for days before month starts
        for ($i = 1; $i < $firstDay; $i++) {
            $week[] = ['day' => null, 'appointments' => []];
        }
        
        // Add days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $week[] = [
                'day' => $day,
                'date' => $date,
                'appointments' => $appointmentsByDate[$date] ?? [],
                'is_today' => $date === date('Y-m-d'),
                'is_weekend' => date('N', strtotime($date)) >= 6
            ];
            
            if (count($week) === 7) {
                $calendar[] = $week;
                $week = [];
            }
        }
        
        // Add remaining days to complete the last week
        if (!empty($week)) {
            while (count($week) < 7) {
                $week[] = ['day' => null, 'appointments' => []];
            }
            $calendar[] = $week;
        }
        
        return $calendar;
    }
}
?>
