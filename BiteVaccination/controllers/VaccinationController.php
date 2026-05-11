<?php
require_once __DIR__ . '/../core/Controller.php';

class VaccinationController extends Controller {
    private $vaccinationModel;
    private $patientModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->vaccinationModel = $this->model('Vaccination');
        $this->patientModel = $this->model('Patient');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Filter parameters
        $filterDate = isset($_GET['date']) ? $_GET['date'] : '';
        $filterType = isset($_GET['type']) ? $_GET['type'] : '';
        $filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
        
        $vaccinations = $this->vaccinationModel->getVaccinationsWithFilters($filterDate, $filterType, $filterStatus, $limit, $offset);
        $totalVaccinations = $this->vaccinationModel->countWithFilters($filterDate, $filterType, $filterStatus);
        $totalPages = ceil($totalVaccinations / $limit);
        
        $this->view('vaccinations/index', [
            'vaccinations' => $vaccinations,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalVaccinations' => $totalVaccinations,
            'filterDate' => $filterDate,
            'filterType' => $filterType,
            'filterStatus' => $filterStatus
        ]);
    }
    
    public function create() {
        $patientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
        $patients = $this->patientModel->findAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patient_id' => (int)$_POST['patient_id'],
                'appointment_id' => !empty($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : null,
                'vaccine_type' => $_POST['vaccine_type'],
                'vaccine_brand' => $this->sanitize($_POST['vaccine_brand']),
                'batch_number' => $this->sanitize($_POST['batch_number']),
                'dose_number' => (int)$_POST['dose_number'],
                'administration_date' => $_POST['administration_date'],
                'administration_time' => $_POST['administration_time'],
                'administered_by' => $_SESSION['user_id'],
                'administration_site' => $_POST['administration_site'],
                'adverse_reactions' => $this->sanitize($_POST['adverse_reactions']),
                'next_dose_date' => !empty($_POST['next_dose_date']) ? $_POST['next_dose_date'] : null,
                'status' => 'scheduled'
            ];
            
            $errors = $this->validateVaccinationData($data);
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                $this->view('vaccinations/create', ['patientId' => $patientId, 'patients' => $patients]);
                return;
            }
            
            if ($this->vaccinationModel->create($data)) {
                $vaccinationId = $this->db->lastInsertId();
                $vaccination = $this->vaccinationModel->findById($vaccinationId);

                if ($data['vaccine_type'] === 'anti_rabies' && $data['dose_number'] === 1 && $data['status'] === 'administered') {
                    $this->createTreatmentProgressIfMissing($data['patient_id'], $data['administration_date']);
                    $this->scheduleAntiRabiesFollowUpDoses($vaccination);
                }

                if ($data['status'] === 'administered') {
                    $this->updateTreatmentProgress($data['patient_id'], $data['dose_number']);
                }
                
                // Log activity
                $this->userModel->logActivity($_SESSION['user_id'], 'create_vaccination', 'vaccinations', $vaccinationId, null, json_encode($data));
                
                $_SESSION['success'] = 'Vaccination recorded successfully!';
                $this->redirect('vaccinations');
            } else {
                $_SESSION['error'] = 'Failed to record vaccination. Please try again.';
                $this->view('vaccinations/create', ['patientId' => $patientId, 'patients' => $patients]);
            }
        } else {
            $this->view('vaccinations/create', ['patientId' => $patientId, 'patients' => $patients]);
        }
    }
    
    public function timeline() {
        $patientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
        
        if (!$patientId) {
            $_SESSION['error'] = 'Patient ID is required.';
            $this->redirect('patients');
        }
        
        $patient = $this->patientModel->findById($patientId);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found.';
            $this->redirect('patients');
        }
        
        $vaccinations = $this->vaccinationModel->getPatientVaccinationStatus($patientId);
        $treatmentProgress = $this->patientModel->getTreatmentProgress($patientId);
        $upcomingVaccinations = $this->vaccinationModel->getUpcomingVaccinations(30);
        
        $this->view('vaccinations/timeline', [
            'patient' => $patient,
            'vaccinations' => $vaccinations,
            'treatmentProgress' => $treatmentProgress,
            'upcomingVaccinations' => $upcomingVaccinations
        ]);
    }
    
    public function reminders() {
        $upcomingVaccinations = $this->vaccinationModel->getUpcomingVaccinations(7);
        $missedVaccinations = $this->vaccinationModel->getMissedVaccinations();
        
        $this->view('vaccinations/reminders', [
            'upcomingVaccinations' => $upcomingVaccinations,
            'missedVaccinations' => $missedVaccinations
        ]);
    }
    
    public function generateCard() {
        $patientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
        
        if (!$patientId) {
            $_SESSION['error'] = 'Patient ID is required.';
            $this->redirect('patients');
        }
        
        $patient = $this->patientModel->findById($patientId);
        $vaccinations = $this->vaccinationModel->generateVaccinationCard($patientId);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found.';
            $this->redirect('patients');
        }
        
        $this->view('vaccinations/card', [
            'patient' => $patient,
            'vaccinations' => $vaccinations
        ]);
    }
    
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vaccinationId = (int)$_POST['vaccination_id'];
            $status = $_POST['status'];
            $administeredBy = $status === 'administered' ? $_SESSION['user_id'] : null;
            
            $vaccination = $this->vaccinationModel->findById($vaccinationId);
            
            if (!$vaccination) {
                echo json_encode(['success' => false, 'message' => 'Vaccination record not found']);
                return;
            }
            
            $oldStatus = $vaccination['status'];
            
            if ($this->vaccinationModel->updateStatus($vaccinationId, $status, $administeredBy)) {
                // Handle anti-rabies follow-up when the first dose is administered
                if ($status === 'administered') {
                    if ($vaccination['dose_number'] === 1 && $vaccination['vaccine_type'] === 'anti_rabies') {
                        $this->createTreatmentProgressIfMissing($vaccination['patient_id'], $vaccination['administration_date']);
                        $this->scheduleAntiRabiesFollowUpDoses($vaccination);
                    }

                    $this->updateTreatmentProgress($vaccination['patient_id'], $vaccination['dose_number']);
                }
                
                // Log activity
                $this->userModel->logActivity($_SESSION['user_id'], 'update_vaccination_status', 'vaccinations', $vaccinationId, $oldStatus, $status);
                
                echo json_encode(['success' => true, 'message' => 'Vaccination status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update vaccination status']);
            }
        }
    }
    
    private function validateVaccinationData($data) {
        $errors = [];
        
        if (empty($data['patient_id'])) {
            $errors[] = 'Patient is required.';
        } else {
            $patient = $this->patientModel->findById($data['patient_id']);
            if (!$patient) {
                $errors[] = 'Selected patient does not exist.';
            }
        }
        
        if (empty($data['vaccine_type'])) {
            $errors[] = 'Vaccine type is required.';
        }
        
        if (empty($data['dose_number'])) {
            $errors[] = 'Dose number is required.';
        } elseif ($data['dose_number'] < 1 || $data['dose_number'] > 10) {
            $errors[] = 'Dose number must be between 1 and 10.';
        }
        
        if (empty($data['administration_date'])) {
            $errors[] = 'Administration date is required.';
        } elseif (!strtotime($data['administration_date'])) {
            $errors[] = 'Invalid administration date.';
        }
        
        if (empty($data['administration_time'])) {
            $errors[] = 'Administration time is required.';
        }
        
        if (empty($data['administration_site'])) {
            $errors[] = 'Administration site is required.';
        }
        
        return $errors;
    }
    
    private function createTreatmentProgressIfMissing($patientId, $startDate) {
        $query = "SELECT * FROM treatment_progress WHERE patient_id = :patient_id ORDER BY treatment_start_date DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        $existingProgress = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingProgress) {
            return;
        }

        $biteId = $this->getLatestBiteId($patientId);
        if (!$biteId) {
            return;
        }

        $scheduleDays = count(VACCINATION_DAYS);
        $nextAppointmentDate = date('Y-m-d', strtotime("+" . VACCINATION_DAYS[1] . " days", strtotime($startDate)));

        $query = "INSERT INTO treatment_progress 
                  (patient_id, bite_id, treatment_start_date, treatment_status, total_doses_required, doses_completed, next_appointment_date, notes)
                  VALUES (:patient_id, :bite_id, :start_date, 'ongoing', :total_doses_required, 1, :next_appointment_date, :notes)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->bindParam(':bite_id', $biteId);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':total_doses_required', $scheduleDays, PDO::PARAM_INT);
        $stmt->bindParam(':next_appointment_date', $nextAppointmentDate);
        $notes = 'Auto-generated anti-rabies treatment schedule after first dose.';
        $stmt->bindParam(':notes', $notes);
        $stmt->execute();
    }

    private function getLatestBiteId($patientId) {
        $query = "SELECT id FROM animal_bites WHERE patient_id = :patient_id ORDER BY bite_date DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['id'] : null;
    }

    private function scheduleAntiRabiesFollowUpDoses($vaccination) {
        if ($vaccination['vaccine_type'] !== 'anti_rabies' || (int)$vaccination['dose_number'] !== 1) {
            return;
        }

        $firstDate = $vaccination['administration_date'];
        $time = $vaccination['administration_time'] ?: '09:00';
        $site = $vaccination['administration_site'];
        $brand = $vaccination['vaccine_brand'];
        $batch = $vaccination['batch_number'];
        $patientId = $vaccination['patient_id'];

        foreach (VACCINATION_DAYS as $index => $day) {
            if ($index === 0) {
                continue;
            }

            $doseNumber = $index + 1;
            $scheduledDate = date('Y-m-d', strtotime("+{$day} days", strtotime($firstDate)));

            if ($this->vaccinationModel->getExistingDose($patientId, $doseNumber, 'anti_rabies')) {
                continue;
            }

            $scheduledData = [
                'patient_id' => $patientId,
                'appointment_id' => null,
                'vaccine_type' => 'anti_rabies',
                'vaccine_brand' => $brand,
                'batch_number' => $batch,
                'dose_number' => $doseNumber,
                'administration_date' => $scheduledDate,
                'administration_time' => $time,
                'administered_by' => null,
                'administration_site' => $site,
                'adverse_reactions' => null,
                'next_dose_date' => $scheduledDate,
                'status' => 'scheduled'
            ];

            $this->vaccinationModel->create($scheduledData);
        }
    }

    private function updateTreatmentProgress($patientId, $doseNumber) {
        // Check if treatment progress exists
        $query = "SELECT * FROM treatment_progress WHERE patient_id = :patient_id ORDER BY treatment_start_date DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        $treatment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($treatment) {
            // Update existing treatment progress
            $newDosesCompleted = min($doseNumber, $treatment['total_doses_required']);
            $nextAppointmentDate = null;
            
            if ($newDosesCompleted < $treatment['total_doses_required']) {
                // Calculate next appointment date based on vaccination schedule
                $nextDays = $this->getNextVaccinationDay($newDosesCompleted);
                if ($nextDays !== null) {
                    $nextAppointmentDate = date('Y-m-d', strtotime("+{$nextDays} days"));
                }
            } else {
                // Treatment completed
                $query = "UPDATE treatment_progress SET treatment_status = 'completed', treatment_end_date = CURDATE(), doses_completed = :doses_completed, next_appointment_date = NULL WHERE patient_id = :patient_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':doses_completed', $newDosesCompleted);
                $stmt->bindParam(':patient_id', $patientId);
                $stmt->execute();
                return;
            }
            
            $query = "UPDATE treatment_progress SET doses_completed = :doses_completed, next_appointment_date = :next_appointment_date WHERE patient_id = :patient_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':doses_completed', $newDosesCompleted);
            $stmt->bindParam(':next_appointment_date', $nextAppointmentDate);
            $stmt->bindParam(':patient_id', $patientId);
            $stmt->execute();
        }
    }
    
    public function myVaccinations() {
        if ($_SESSION['role'] !== ROLE_PATIENT) {
            $_SESSION['error'] = 'Access denied. This page is for patients only.';
            $this->redirect('dashboard');
        }
        
        $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
        if (!$patient) {
            $_SESSION['error'] = 'Patient profile not found.';
            $this->redirect('dashboard');
        }
        
        $upcomingVaccinations = $this->vaccinationModel->getPatientUpcomingVaccinations($patient['id']);
        $vaccinationHistory = $this->vaccinationModel->getPatientVaccinationStatus($patient['id']);
        
        $this->view('vaccinations/my_vaccinations', [
            'patient' => $patient,
            'upcomingVaccinations' => $upcomingVaccinations,
            'vaccinationHistory' => $vaccinationHistory
        ]);
    }
    
    private function getNextVaccinationDay($currentDose) {
        $schedule = VACCINATION_DAYS;
        return $schedule[$currentDose] ?? null;
    }
    
    public function schedule() {
        // Only admin and staff can view vaccination schedules
        if ($_SESSION['role'] === ROLE_PATIENT) {
            $_SESSION['error'] = 'Access denied.';
            $this->redirect('dashboard');
        }
        
        $schedules = $this->vaccinationModel->getVaccinationScheduleForConfirmedAppointments();
        $patients = $this->vaccinationModel->getPatientsWithConfirmedAppointments();
        
        // Calculate statistics
        $todayCount = 0;
        $weekCount = 0;
        $today = date('Y-m-d');
        $weekFromNow = date('Y-m-d', strtotime('+7 days'));
        
        foreach ($schedules as $schedule) {
            if ($schedule['administration_date'] === $today) {
                $todayCount++;
            }
            if ($schedule['administration_date'] >= $today && $schedule['administration_date'] <= $weekFromNow) {
                $weekCount++;
            }
        }
        
        $this->view('vaccinations/schedule', [
            'schedules' => $schedules,
            'patients' => $patients,
            'todayCount' => $todayCount,
            'weekCount' => $weekCount
        ]);
    }
    
    public function createSchedule() {
        // Only admin can create vaccination schedules
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Access denied. Only administrators can create vaccination schedules.';
            $this->redirect('vaccinations/schedule');
        }
        
        // Test database connection first
        $dbTest = $this->vaccinationModel->testDatabaseConnection();
        
        // Get all patients for scheduling (includes those with scheduled, confirmed, or completed appointments)
        $patients = $this->vaccinationModel->getAllPatientsForScheduling();
        
        // Get available appointments for linking
        $appointmentModel = $this->model('Appointment');
        $appointments = $appointmentModel->getAppointmentsWithFilters('', 'confirmed', 50, 0);
        
        $this->view('vaccinations/create_schedule', [
            'patients' => $patients,
            'appointments' => $appointments,
            'db_test' => $dbTest
        ]);
    }
    
    public function storeSchedule() {
        // Only admin can create vaccination schedules
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Access denied. Only administrators can create vaccination schedules.';
            $this->redirect('vaccinations/schedule');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patient_id' => (int)$_POST['patient_id'],
                'appointment_id' => !empty($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : null,
                'vaccine_type' => $_POST['vaccine_type'],
                'vaccine_brand' => $this->sanitize($_POST['vaccine_brand']),
                'batch_number' => $this->sanitize($_POST['batch_number']),
                'dose_number' => (int)$_POST['dose_number'],
                'administration_date' => $_POST['administration_date'],
                'administration_time' => $_POST['administration_time'],
                'administration_site' => $_POST['administration_site'],
                'next_dose_date' => !empty($_POST['next_dose_date']) ? $_POST['next_dose_date'] : null
            ];
            
            $errors = $this->validateVaccinationData($data);
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                $this->redirect('vaccinations/create-schedule');
                return;
            }
            
            if ($this->vaccinationModel->createUpcomingVaccination($data)) {
                $vaccinationId = $this->db->lastInsertId();
                
                // Log activity
                $this->userModel->logActivity($_SESSION['user_id'], 'create_vaccination_schedule', 'vaccinations', $vaccinationId, null, json_encode($data));
                
                $_SESSION['success'] = 'Vaccination schedule created successfully!';
                $this->redirect('vaccinations/schedule');
            } else {
                $_SESSION['error'] = 'Failed to create vaccination schedule. Please try again.';
                $this->redirect('vaccinations/create-schedule');
            }
        }
    }
    
    public function patientSchedule() {
        // For patients to view their own vaccination schedule
        if ($_SESSION['role'] !== ROLE_PATIENT) {
            $_SESSION['error'] = 'Access denied. This page is for patients only.';
            $this->redirect('dashboard');
        }
        
        $patient = $this->patientModel->findByUserId($_SESSION['user_id']);
        if (!$patient) {
            $_SESSION['error'] = 'Patient profile not found.';
            $this->redirect('dashboard');
        }
        
        $schedule = $this->vaccinationModel->getPatientVaccinationSchedule($patient['id']);
        
        $this->view('vaccinations/patient_schedule', [
            'patient' => $patient,
            'schedule' => $schedule
        ]);
    }
}
?>
