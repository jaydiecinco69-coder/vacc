<?php
require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller {
    private $userModel;
    private $patientModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = $this->model('User');
        $this->patientModel = $this->model('Patient');
    }
    
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            $password = $_POST['password'];
            $remember = isset($_POST['remember']) ? true : false;
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Please enter both email and password.';
                $this->view('auth/login');
                return;
            }
            
            $user = $this->userModel->authenticateByEmail($email, $password);
            
            if ($user) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['profile_picture'] = $user['profile_picture'];
                
                if ($remember) {
                    setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), '/');
                }
                
                $this->userModel->logActivity($user['id'], 'login', 'users', $user['id']);
                
                $_SESSION['success'] = 'Welcome back, ' . $user['full_name'] . '!';
                $this->redirect('dashboard');
            } else {
                $_SESSION['error'] = 'Invalid email or password.';
                $this->view('auth/login');
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            $first_name = $this->sanitize($_POST['first_name']);
            $last_name = $this->sanitize($_POST['last_name']);
            
            // Generate username from email (prefix before @)
            $username = explode('@', $email)[0];
            
            // Ensure unique username by appending timestamp if needed
            $existingUser = $this->userModel->findByUsername($username);
            if ($existingUser) {
                $username = $username . '_' . time();
            }
            
            // Data for validation
            $validationData = [
                'email' => $email,
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password'],
                'full_name' => $first_name . ' ' . $last_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $this->sanitize($_POST['phone']),
                'role' => 'patient'
            ];
            
            $errors = $this->validateRegistration($validationData);
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $validationData;
                $this->view('auth/register');
                return;
            }
            
            // Data for database insertion (only fields that exist in users table)
            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $_POST['password'],
                'full_name' => $first_name . ' ' . $last_name,
                'phone' => $this->sanitize($_POST['phone']),
                'role' => 'patient'
            ];
            
            // Start transaction to ensure both user and patient are created
            $this->db->beginTransaction();
            
            try {
                // Create user account first
                if ($this->userModel->create($data)) {
                    $userId = $this->db->lastInsertId();
                    
                    // Generate unique patient_id
                    $patientId = 'PAT' . str_pad($userId, 6, '0', STR_PAD_LEFT);
                    
                    // Create patient record automatically for all new accounts
                    $patientData = [
                        'user_id' => $userId,
                        'patient_id' => $patientId,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'phone' => $this->sanitize($_POST['phone']),
                        'email' => $email
                    ];
                    
                    if ($this->patientModel->create($patientData)) {
                        $this->db->commit();
                        $_SESSION['success'] = 'Registration successful! Your patient ID has been created. Please login.';
                        $this->redirect('auth/login');
                    } else {
                        throw new Exception('Failed to create patient record');
                    }
                } else {
                    throw new Exception('Failed to create user account');
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
                error_log('Registration error: ' . $e->getMessage());
                $this->view('auth/register');
            }
        } else {
            $this->view('auth/register');
        }
    }
    
    public function logout() {
        if ($this->isLoggedIn()) {
            $this->userModel->logActivity($_SESSION['user_id'], 'logout', 'users', $_SESSION['user_id']);
        }
        
        session_destroy();
        setcookie('remember_username', '', time() - 3600, '/');
        
        $this->redirect('');
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email']);
            
            if (empty($email)) {
                $_SESSION['error'] = 'Please enter your email address.';
                $this->view('auth/forgot-password');
                return;
            }
            
            if (!$this->validateEmail($email)) {
                $_SESSION['error'] = 'Please enter a valid email address.';
                $this->view('auth/forgot-password');
                return;
            }
            
            $user = $this->userModel->findByEmail($email);
            
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $_SESSION['reset_token'] = $token;
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_token_expiry'] = time() + 3600;
                
                $_SESSION['success'] = 'Password reset instructions have been sent to your email.';
                $this->redirect('auth/login');
            } else {
                $_SESSION['error'] = 'No account found with this email address.';
                $this->view('auth/forgot-password');
            }
        } else {
            $this->view('auth/forgot-password');
        }
    }
    
    public function resetPassword() {
        if (!isset($_GET['token']) || !isset($_SESSION['reset_token'])) {
            $this->redirect('auth/login');
        }
        
        if ($_GET['token'] !== $_SESSION['reset_token'] || time() > $_SESSION['reset_token_expiry']) {
            unset($_SESSION['reset_token'], $_SESSION['reset_email'], $_SESSION['reset_token_expiry']);
            $_SESSION['error'] = 'Invalid or expired reset token.';
            $this->redirect('auth/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            if (empty($password) || empty($confirm_password)) {
                $_SESSION['error'] = 'Please enter both password fields.';
                $this->view('auth/reset-password');
                return;
            }
            
            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Passwords do not match.';
                $this->view('auth/reset-password');
                return;
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters long.';
                $this->view('auth/reset-password');
                return;
            }
            
            $user = $this->userModel->findByEmail($_SESSION['reset_email']);
            
            if ($user && $this->userModel->updatePassword($user['id'], $password)) {
                unset($_SESSION['reset_token'], $_SESSION['reset_email'], $_SESSION['reset_token_expiry']);
                $_SESSION['success'] = 'Password reset successful! Please login with your new password.';
                $this->redirect('auth/login');
            } else {
                $_SESSION['error'] = 'Password reset failed. Please try again.';
                $this->view('auth/reset-password');
            }
        } else {
            $this->view('auth/reset-password');
        }
    }
    
    public function changePassword() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = 'Please complete all password fields.';
                $this->view('auth/change-password');
                return;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'New passwords do not match.';
                $this->view('auth/change-password');
                return;
            }

            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'New password must be at least 6 characters long.';
                $this->view('auth/change-password');
                return;
            }

            $user = $this->userModel->findById($_SESSION['user_id']);

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $_SESSION['error'] = 'Current password is incorrect.';
                $this->view('auth/change-password');
                return;
            }

            if ($this->userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
                $_SESSION['success'] = 'Password changed successfully.';
                $this->redirect('dashboard');
            } else {
                $_SESSION['error'] = 'Failed to update password. Please try again.';
                $this->view('auth/change-password');
            }
        } else {
            $this->view('auth/change-password');
        }
    }
    
    private function validateRegistration($data) {
        $errors = [];
        
        if (empty($data['email'])) {
            $errors[] = 'Email is required.';
        } elseif (!$this->validateEmail($data['email'])) {
            $errors[] = 'Please enter a valid email address.';
        } elseif ($this->userModel->findByEmail($data['email'])) {
            $errors[] = 'Email already exists.';
        }
        
        if (empty($data['password'])) {
            $errors[] = 'Password is required.';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }
        
        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Passwords do not match.';
        }
        
        if (empty($data['first_name'])) {
            $errors[] = 'First name is required.';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Last name is required.';
        }
        
        return $errors;
    }
}
?>
