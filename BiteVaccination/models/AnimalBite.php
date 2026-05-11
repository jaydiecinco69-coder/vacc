<?php
class AnimalBite extends Model {
    protected $table = 'animal_bites';
    
    public function create($data) {
        return parent::create($data);
    }
    
    public function getPatientBites($patientId) {
        $query = "SELECT * FROM {$this->table} WHERE patient_id = :patient_id ORDER BY bite_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBiteById($biteId) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $biteId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getRecentBite($patientId) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE patient_id = :patient_id 
                  ORDER BY bite_date DESC, bite_time DESC 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($biteId, $data) {
        return parent::update($biteId, $data);
    }
    
    public function delete($biteId) {
        return parent::delete($biteId);
    }
    
    public function getBiteStats() {
        $query = "SELECT 
                    animal_type,
                    COUNT(*) as count,
                    SUM(CASE WHEN exposure_type = 'category_iii' THEN 1 ELSE 0 END) as severe_cases
                  FROM {$this->table}
                  GROUP BY animal_type
                  ORDER BY count DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMonthlyBiteStats($months = 12) {
        $query = "SELECT 
                    DATE_FORMAT(bite_date, '%Y-%m') as month,
                    COUNT(*) as total_bites,
                    SUM(CASE WHEN animal_type = 'dog' THEN 1 ELSE 0 END) as dog_bites,
                    SUM(CASE WHEN animal_type = 'cat' THEN 1 ELSE 0 END) as cat_bites,
                    SUM(CASE WHEN exposure_type = 'category_iii' THEN 1 ELSE 0 END) as severe_cases
                  FROM {$this->table}
                  WHERE bite_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                  GROUP BY DATE_FORMAT(bite_date, '%Y-%m')
                  ORDER BY month ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
