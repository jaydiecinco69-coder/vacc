<?php
class Patient extends Model {
    protected $table = 'patients';
    
    public function create($data) {
        if (!isset($data['patient_id'])) {
            $data['patient_id'] = $this->generatePatientId();
        }
        
        return parent::create($data);
    }
    
    private function generatePatientId() {
        $prefix = 'PT';
        $year = date('Y');
        $sequence = $this->getSequenceNumber();
        return $prefix . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    private function getSequenceNumber() {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE YEAR(created_at) = YEAR(CURDATE())";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] + 1;
    }
    
    public function findByPatientId($patientId) {
        $query = "SELECT * FROM {$this->table} WHERE patient_id = :patient_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByUserId($userId) {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function search($term, $limit = 10, $offset = 0) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE patient_id LIKE :term 
                  OR first_name LIKE :term 
                  OR last_name LIKE :term 
                  OR phone LIKE :term 
                  OR email LIKE :term
                  ORDER BY created_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = "%$term%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNewPatients($days = 7) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                  ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getFollowUpPatients() {
        $query = "SELECT p.*, tp.next_appointment_date 
                  FROM {$this->table} p 
                  LEFT JOIN treatment_progress tp ON p.id = tp.patient_id 
                  WHERE tp.treatment_status = 'ongoing' 
                  AND tp.next_appointment_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
                  ORDER BY tp.next_appointment_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTreatmentProgress($patientId) {
        $query = "SELECT tp.*, ab.bite_date, ab.animal_type 
                  FROM treatment_progress tp 
                  LEFT JOIN animal_bites ab ON tp.bite_id = ab.id 
                  WHERE tp.patient_id = :patient_id 
                  ORDER BY tp.treatment_start_date DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPatientStats() {
        $query = "SELECT 
                    COUNT(*) as total_patients,
                    SUM(CASE WHEN created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as new_this_month,
                    SUM(CASE WHEN created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as new_this_week,
                    SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as new_this_year
                  FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAgeDistribution() {
        $query = "SELECT 
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18 THEN 'Under 18'
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 35 THEN '18-35'
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 36 AND 50 THEN '36-50'
                        ELSE 'Over 50'
                    END as age_group,
                    COUNT(*) as count
                  FROM {$this->table}
                  GROUP BY age_group
                  ORDER BY age_group";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateQRCode($patientId, $qrCode) {
        $query = "UPDATE {$this->table} SET qr_code = :qr_code, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':qr_code', $qrCode);
        $stmt->bindParam(':id', $patientId);
        
        return $stmt->execute();
    }
}
?>
