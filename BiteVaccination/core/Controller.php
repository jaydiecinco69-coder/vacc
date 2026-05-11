<?php
class Controller {
    protected $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/$view.php";
    }
    
    public function model($model) {
        require_once __DIR__ . "/../models/$model.php";
        return new $model($this->db);
    }
    
    public function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }
    
    public function requireRole($role) {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
        
        if ($_SESSION['user_role'] !== $role) {
            $_SESSION['error'] = 'Access denied. Insufficient permissions.';
            $this->redirect('dashboard');
        }
    }
    
    public function sanitize($input) {
        return htmlspecialchars(trim($input ?? ''), ENT_QUOTES, 'UTF-8');
    }
    
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public function generateCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public function verifyCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>
