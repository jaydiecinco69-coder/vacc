<?php
class User extends Model {
    protected $table = 'users';
    
    public function authenticate($username, $password) {
        $query = "SELECT * FROM {$this->table} WHERE username = :username AND is_active = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function authenticateByEmail($email, $password) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email AND is_active = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function findByUsername($username) {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return parent::create($data);
    }
    
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE {$this->table} SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        
        return $stmt->execute();
    }
    
    public function getAllUsers($role = null, $limit = null, $offset = 0) {
        $query = "SELECT id, username, email, full_name, role, phone, is_active, created_at FROM {$this->table}";
        
        if ($role) {
            $query .= " WHERE role = :role";
        }
        
        $query .= " ORDER BY created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($query);
        
        if ($role) {
            $stmt->bindParam(':role', $role);
        }
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countActive() {
        $query = "SELECT COUNT(*) as active_count FROM {$this->table} WHERE is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['active_count'] : 0;
    }
    
    public function toggleStatus($userId) {
        $query = "UPDATE {$this->table} SET is_active = NOT is_active WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        
        return $stmt->execute();
    }
    
    public function getUserStats() {
        $query = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admin_count,
                    SUM(CASE WHEN role = 'staff' THEN 1 ELSE 0 END) as staff_count,
                    SUM(CASE WHEN role = 'receptionist' THEN 1 ELSE 0 END) as receptionist_count,
                    SUM(CASE WHEN role = 'patient' THEN 1 ELSE 0 END) as patient_count,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_users
                  FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function logActivity($userId, $action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null) {
        $query = "INSERT INTO activity_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
                  VALUES (:user_id, :action, :table_name, :record_id, :old_values, :new_values, :ip_address, :user_agent)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':table_name', $tableName);
        $stmt->bindParam(':record_id', $recordId);
        $stmt->bindParam(':old_values', $oldValues);
        $stmt->bindParam(':new_values', $newValues);
        $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT']);
        
        return $stmt->execute();
    }
}
?>
