<?php
require_once __DIR__ . '/../core/Controller.php';

class PatientController extends Controller {
    private $patientModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->patientModel = $this->model('Patient');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $search = isset($_GET['search']) ? $this->sanitize($_GET['search']) : '';
        
        if ($search) {
            $patients = $this->patientModel->search($search, $limit, $offset);
            $totalPatients = $this->patientModel->countSearchResults($search);
        } else {
            $patients = $this->patientModel->findAll($limit, $offset);
            $totalPatients = $this->patientModel->count();
        }
        
        $totalPages = ceil($totalPatients / $limit);
        
        $this->view('patients/index', [
            'patients' => $patients,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPatients' => $totalPatients,
            'search' => $search
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $this->sanitize($_POST['first_name']),
                'last_name' => $this->sanitize($_POST['last_name']),
                'middle_name' => $this->sanitize($_POST['middle_name']),
                'birth_date' => $_POST['birth_date'],
                'gender' => $_POST['gender'],
                'phone' => $this->sanitize($_POST['phone']),
                'email' => $this->sanitize($_POST['email']),
                'address' => $this->sanitize($_POST['address']),
                'emergency_contact_name' => $this->sanitize($_POST['emergency_contact_name']),
                'emergency_contact_phone' => $this->sanitize($_POST['emergency_contact_phone']),
                'blood_type' => $this->sanitize($_POST['blood_type']),
                'allergies' => $this->sanitize($_POST['allergies']),
                'medical_history' => $this->sanitize($_POST['medical_history'])
            ];
            
            $errors = $this->validatePatientData($data);
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                $this->view('patients/create');
                return;
            }
            
            if ($this->patientModel->create($data)) {
                $patientId = $this->db->lastInsertId();
                
                // Generate QR code (placeholder - would need QR code library)
                $qrCode = 'QR_' . $data['patient_id'] . '_' . time();
                $this->patientModel->updateQRCode($patientId, $qrCode);
                
                // Log activity
                $this->userModel->logActivity($_SESSION['user_id'], 'create_patient', 'patients', $patientId, null, json_encode($data));
                
                $_SESSION['success'] = 'Patient registered successfully!';
                $this->redirect('patients');
            } else {
                $_SESSION['error'] = 'Failed to register patient. Please try again.';
                $this->view('patients/create');
            }
        } else {
            $this->view('patients/create');
        }
    }
    
    public function edit() {
        $patientId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$patientId) {
            $_SESSION['error'] = 'Patient ID is required.';
            $this->redirect('patients');
        }
        
        $patient = $this->patientModel->findById($patientId);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found.';
            $this->redirect('patients');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $this->sanitize($_POST['first_name']),
                'last_name' => $this->sanitize($_POST['last_name']),
                'middle_name' => $this->sanitize($_POST['middle_name'] ?? ''),
                'birth_date' => $_POST['birth_date'],
                'gender' => $_POST['gender'],
                'phone' => $this->sanitize($_POST['contact_number']),
                'address' => $this->sanitize($_POST['address']),
                'emergency_contact_name' => $this->sanitize($_POST['emergency_contact_name']),
                'emergency_contact_phone' => $this->sanitize($_POST['emergency_contact_number'])
            ];
            
            $errors = $this->validatePatientData($data, true);
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                $this->view('patients/edit', ['patient' => $patient]);
                return;
            }
            
            $oldData = $patient;
            
            if ($this->patientModel->update($patientId, $data)) {
                // Log activity
                $this->userModel->logActivity($_SESSION['user_id'], 'update_patient', 'patients', $patientId, json_encode($oldData), json_encode($data));
                
                $_SESSION['success'] = 'Patient information updated successfully!';
                $this->redirect('patients');
            } else {
                $_SESSION['error'] = 'Failed to update patient information. Please try again.';
                $this->view('patients/edit', ['patient' => $patient]);
            }
        } else {
            $this->view('patients/edit', ['patient' => $patient]);
        }
    }
    
    public function show() {
        $patientId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$patientId) {
            $_SESSION['error'] = 'Patient ID is required.';
            $this->redirect('patients');
        }
        
        $patient = $this->patientModel->findById($patientId);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found.';
            $this->redirect('patients');
        }
        
        // Get additional patient data
        $animalBites = $this->getAnimalBites($patientId);
        $appointments = $this->getAppointments($patientId);
        $vaccinations = $this->getVaccinations($patientId);
        $treatmentProgress = $this->patientModel->getTreatmentProgress($patientId);
        
        $this->view('patients/view', [
            'patient' => $patient,
            'animalBites' => $animalBites,
            'appointments' => $appointments,
            'vaccinations' => $vaccinations,
            'treatmentProgress' => $treatmentProgress
        ]);
    }
    
    public function delete() {
        $patientId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$patientId) {
            $_SESSION['error'] = 'Patient ID is required.';
            $this->redirect('patients');
        }
        
        $patient = $this->patientModel->findById($patientId);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found.';
            $this->redirect('patients');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                if ($this->patientModel->delete($patientId)) {
                    // Log activity
                    $this->userModel->logActivity($_SESSION['user_id'], 'delete_patient', 'patients', $patientId, json_encode($patient), null);
                    
                    $_SESSION['success'] = 'Patient deleted successfully!';
                    $this->redirect('patients');
                } else {
                    $_SESSION['error'] = 'Failed to delete patient. Please try again.';
                    $this->redirect('patients');
                }
            } else {
                $this->redirect('patients');
            }
        } else {
            $this->view('patients/delete', ['patient' => $patient]);
        }
    }
    
    private function validatePatientData($data, $isUpdate = false) {
        $errors = [];
        
        if (empty($data['first_name'])) {
            $errors[] = 'First name is required.';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Last name is required.';
        }
        
        if (empty($data['birth_date'])) {
            $errors[] = 'Birth date is required.';
        } elseif (!strtotime($data['birth_date'])) {
            $errors[] = 'Invalid birth date format.';
        }
        
        if (empty($data['gender'])) {
            $errors[] = 'Gender is required.';
        }
        
        if (empty($data['phone'])) {
            $errors[] = 'Phone number is required.';
        }
        
                
        if (empty($data['address'])) {
            $errors[] = 'Address is required.';
        }
        
        return $errors;
    }
    
    private function getAnimalBites($patientId) {
        $query = "SELECT * FROM animal_bites WHERE patient_id = :patient_id ORDER BY bite_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getAppointments($patientId) {
        $query = "SELECT a.*, u.full_name as staff_name 
                  FROM appointments a 
                  LEFT JOIN users u ON a.staff_id = u.id 
                  WHERE a.patient_id = :patient_id 
                  ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                  LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getVaccinations($patientId) {
        $query = "SELECT v.*, u.full_name as administered_by_name 
                  FROM vaccinations v 
                  LEFT JOIN users u ON v.administered_by = u.id 
                  WHERE v.patient_id = :patient_id 
                  ORDER BY v.dose_number ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
