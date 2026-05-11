<?php
require_once __DIR__ . '/../core/Controller.php';

class DashboardController extends Controller {
    private $userModel;
    private $patientModel;
    private $appointmentModel;
    private $vaccinationModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->userModel = $this->model('User');
        $this->patientModel = $this->model('Patient');
        $this->appointmentModel = $this->model('Appointment');
        $this->vaccinationModel = $this->model('Vaccination');
    }
    
    public function index() {
        $userRole = $_SESSION['role'];
        
        switch ($userRole) {
            case ROLE_ADMIN:
                $this->adminDashboard();
                break;
            case ROLE_RECEPTIONIST:
                $this->receptionistDashboard();
                break;
            case ROLE_PATIENT:
                $this->patientDashboard();
                break;
            default:
                $this->redirect('auth/logout');
        }
    }
    
    private function adminDashboard() {
        $stats = [
            'total_patients' => $this->patientModel->count(),
            'total_appointments' => $this->appointmentModel->count(),
            'completed_vaccinations' => $this->vaccinationModel->countCompleted(),
            'active_users' => $this->userModel->countActive(),
            'user_stats' => $this->userModel->getUserStats()
        ];
        
        $recentActivities = $this->getRecentActivities();
        $upcomingAppointments = $this->appointmentModel->getUpcoming(5);
        $vaccinationStats = $this->vaccinationModel->getMonthlyStats();
        
        $this->view('dashboard/admin', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'upcomingAppointments' => $upcomingAppointments,
            'vaccinationStats' => $vaccinationStats
        ]);
    }
    
    private function staffDashboard() {
        $todayAppointments = $this->appointmentModel->getTodayAppointments();
        $patientQueue = $this->appointmentModel->getPatientQueue();
        $vaccinationsToday = $this->vaccinationModel->getTodayVaccinations();
        $followUpPatients = $this->patientModel->getFollowUpPatients();
        
        $this->view('dashboard/staff', [
            'todayAppointments' => $todayAppointments,
            'patientQueue' => $patientQueue,
            'vaccinationsToday' => $vaccinationsToday,
            'followUpPatients' => $followUpPatients
        ]);
    }
    
    private function receptionistDashboard() {
        $todayAppointments = $this->appointmentModel->getTodayAppointments();
        $newPatients = $this->patientModel->getNewPatients(7);
        $pendingAppointments = $this->appointmentModel->getPendingAppointments();
        
        $this->view('dashboard/receptionist', [
            'todayAppointments' => $todayAppointments,
            'newPatients' => $newPatients,
            'pendingAppointments' => $pendingAppointments
        ]);
    }
    
    private function patientDashboard() {
        $userId = $_SESSION['user_id'];
        $patientInfo = $this->patientModel->findByUserId($userId);
        
        if (!$patientInfo) {
            $user = $this->userModel->findById($userId);
            $nameParts = explode(' ', trim($user['full_name']));
            $firstName = $nameParts[0] ?? '';
            $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';
            $patientInfo = [
                'id' => 0,
                'user_id' => $userId,
                'patient_id' => 'N/A',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $user['phone'] ?? '',
                'email' => $user['email'] ?? '',
                'address' => $user['address'] ?? '',
                'emergency_contact_name' => '',
                'emergency_contact_phone' => '',
                'blood_type' => '',
                'allergies' => '',
                'medical_history' => '',
            ];
            $profileMissing = true;
        } else {
            $profileMissing = false;
        }
        
        $upcomingAppointments = $this->appointmentModel->getPatientAppointments($patientInfo['id']);
        $vaccinationStatus = $this->vaccinationModel->getPatientVaccinationStatus($patientInfo['id']);
        $treatmentProgress = $this->patientModel->getTreatmentProgress($patientInfo['id']);
        $healthTip = $this->getHealthTip();
        
        $this->view('dashboard/patient', [
            'patientInfo' => $patientInfo,
            'upcomingAppointments' => $upcomingAppointments,
            'vaccinationStatus' => $vaccinationStatus,
            'treatmentProgress' => $treatmentProgress,
            'profileMissing' => $profileMissing,
            'healthTip' => $healthTip
        ]);
    }
    
    private function getRecentActivities() {
        $query = "SELECT al.*, u.full_name, u.role 
                  FROM activity_logs al 
                  LEFT JOIN users u ON al.user_id = u.id 
                  ORDER BY al.created_at DESC 
                  LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getHealthTip() {
        $url = 'https://api.publicapis.org/entries?category=Health&https=true';
        $response = false;

        if (function_exists('curl_version')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $response = @file_get_contents($url);
        }

        if ($response) {
            $data = json_decode($response, true);
            if (!empty($data['entries'][0])) {
                $entry = $data['entries'][0];
                return [
                    'title' => $entry['API'] ?? 'Health Tip',
                    'description' => $entry['Description'] ?? 'Stay informed about health updates.',
                    'link' => $entry['Link'] ?? ''
                ];
            }
        }

        return [
            'title' => 'Health Tip',
            'description' => 'Remember to follow your vaccination schedule and report any symptoms early.',
            'link' => ''
        ];
    }
    
    public function editProfile() {
        if ($_SESSION['role'] !== ROLE_PATIENT) {
            $_SESSION['error'] = 'Access denied. This page is for patients only.';
            $this->redirect('dashboard');
        }
        
        $userId = $_SESSION['user_id'];
        $patientInfo = $this->patientModel->findByUserId($userId);
        
        if (!$patientInfo) {
            $_SESSION['error'] = 'Patient profile not found.';
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $this->sanitize($_POST['first_name'] ?? ''),
                'last_name' => $this->sanitize($_POST['last_name'] ?? ''),
                'middle_name' => $this->sanitize($_POST['middle_name'] ?? ''),
                'birth_date' => $_POST['birth_date'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'phone' => $this->sanitize($_POST['phone'] ?? ''),
                'email' => $this->sanitize($_POST['email'] ?? ''),
                'address' => $this->sanitize($_POST['address'] ?? ''),
                'emergency_contact_name' => $this->sanitize($_POST['emergency_contact_name'] ?? ''),
                'emergency_contact_phone' => $this->sanitize($_POST['emergency_contact_phone'] ?? ''),
                'blood_type' => $_POST['blood_type'] ?? '',
                'allergies' => $this->sanitize($_POST['allergies'] ?? ''),
                'medical_history' => $this->sanitize($_POST['medical_history'] ?? '')
            ];
            
            $errors = $this->validateProfileData($data);
            
            if (!empty($errors)) {
                if ($this->patientModel->update($userId, $data)) {
                    // Update user's name and email if changed
                    if ($data['first_name'] || $data['last_name']) {
                        $userUpdate = [
                            'full_name' => trim($data['first_name'] . ' ' . $data['last_name']),
                            'email' => $data['email'],
                            'phone' => $data['phone']
                        ];
                        $this->userModel->update($userId, $userUpdate);
                    }
                    
                    $_SESSION['success'] = 'Profile updated successfully!';
                    $this->redirect('dashboard');
                } else {
                    $_SESSION['error'] = 'Failed to update profile. Please try again.';
                }
            } else {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
            }
        }
        
        $this->view('dashboard/edit_profile', ['patient' => $patientInfo]);
    }
    
    private function validateProfileData($data) {
        $errors = [];
        
        if (empty($data['first_name'])) {
            $errors[] = 'First name is required.';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Last name is required.';
        }
        
        if (empty($data['phone'])) {
            $errors[] = 'Phone number is required.';
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (!empty($data['birth_date']) && !strtotime($data['birth_date'])) {
            $errors[] = 'Please enter a valid birth date.';
        }
        
        return $errors;
    }
}
?>
