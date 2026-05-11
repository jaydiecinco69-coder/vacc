<?php
class Vaccination extends Model {
    protected $table = 'vaccinations';
    
    public function create($data) {
        return parent::create($data);
    }
    
    public function getTodayVaccinations() {
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, u.full_name as administered_by_name
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  LEFT JOIN users u ON v.administered_by = u.id
                  WHERE v.administration_date = CURDATE()
                  ORDER BY v.administration_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPatientVaccinationStatus($patientId) {
        $query = "SELECT v.*, p.first_name, p.last_name, tp.total_doses_required
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  LEFT JOIN treatment_progress tp ON v.patient_id = tp.patient_id
                  WHERE v.patient_id = :patient_id
                  ORDER BY v.dose_number ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExistingDose($patientId, $doseNumber, $vaccineType) {
        $query = "SELECT id FROM {$this->table}
                  WHERE patient_id = :patient_id
                  AND dose_number = :dose_number
                  AND vaccine_type = :vaccine_type
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId, PDO::PARAM_INT);
        $stmt->bindParam(':dose_number', $doseNumber, PDO::PARAM_INT);
        $stmt->bindParam(':vaccine_type', $vaccineType);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getCompleted() {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'administered'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function countCompleted() {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'administered'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function getMonthlyStats() {
        $query = "SELECT 
                    MONTH(administration_date) as month,
                    COUNT(*) as vaccinations,
                    SUM(CASE WHEN vaccine_type = 'anti_rabies' THEN 1 ELSE 0 END) as anti_rabies,
                    SUM(CASE WHEN vaccine_type = 'tetanus' THEN 1 ELSE 0 END) as tetanus,
                    SUM(CASE WHEN vaccine_type = 'immunoglobulin' THEN 1 ELSE 0 END) as immunoglobulin
                  FROM {$this->table}
                  WHERE administration_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                  GROUP BY MONTH(administration_date)
                  ORDER BY month ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUpcomingVaccinations($days = 7) {
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, p.phone
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  WHERE v.status = 'scheduled'
                  AND v.administration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
                  ORDER BY v.administration_date ASC, v.administration_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVaccinationsWithFilters($date, $type, $status, $limit = 10, $offset = 0) {
        $conditions = [];
        $params = [];
        
        if (!empty($date)) {
            $conditions[] = "v.administration_date = :date";
            $params[':date'] = $date;
        }
        
        if (!empty($type)) {
            $conditions[] = "v.vaccine_type = :type";
            $params[':type'] = $type;
        }
        
        if (!empty($status)) {
            $conditions[] = "v.status = :status";
            $params[':status'] = $status;
        }
        
        $whereClause = empty($conditions) ? '1' : implode(' AND ', $conditions);
        
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, u.full_name as administered_by_name
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  LEFT JOIN users u ON v.administered_by = u.id
                  WHERE {$whereClause}
                  ORDER BY v.administration_date DESC, v.administration_time DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countWithFilters($date, $type, $status, $patientId = 0) {
        $conditions = [];
        $params = [];
        
        if (!empty($date)) {
            $conditions[] = "administration_date = :date";
            $params[':date'] = $date;
        }
        
        if (!empty($type)) {
            $conditions[] = "vaccine_type = :type";
            $params[':type'] = $type;
        }
        
        if (!empty($status)) {
            $conditions[] = "status = :status";
            $params[':status'] = $status;
        }
        
        if ($patientId > 0) {
            $conditions[] = "patient_id = :patient_id";
            $params[':patient_id'] = $patientId;
        }
        
        $whereClause = empty($conditions) ? '1' : implode(' AND ', $conditions);
        $query = "SELECT COUNT(*) as count FROM {$this->table} v WHERE {$whereClause}";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function getMissedVaccinations() {
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, p.phone
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  WHERE v.status = 'scheduled'
                  AND v.administration_date < CURDATE()
                  ORDER BY v.administration_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($vaccinationId, $status, $administeredBy = null) {
        $query = "UPDATE {$this->table} SET status = :status, updated_at = CURRENT_TIMESTAMP";
        $params = [':status' => $status, ':id' => $vaccinationId];
        
        if ($administeredBy) {
            $query .= ", administered_by = :administered_by";
            $params[':administered_by'] = $administeredBy;
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        return $stmt->execute();
    }
    
    public function getVaccinationSchedule($patientId) {
        $query = "SELECT 
                    v.dose_number,
                    v.administration_date,
                    v.administration_time,
                    v.status,
                    v.vaccine_type,
                    v.next_dose_date
                  FROM {$this->table} v
                  WHERE v.patient_id = :patient_id
                  ORDER BY v.dose_number ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVaccineInventoryStats() {
        $query = "SELECT 
                    vaccine_type,
                    SUM(quantity_remaining) as total_remaining,
                    SUM(quantity_used) as total_used,
                    COUNT(*) as product_types
                  FROM vaccine_inventory
                  WHERE status = 'active'
                  GROUP BY vaccine_type";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAdverseReactions() {
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  WHERE v.adverse_reactions IS NOT NULL
                  AND v.adverse_reactions != ''
                  ORDER BY v.administration_date DESC
                  LIMIT 50";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function generateVaccinationCard($patientId) {
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, p.birth_date, p.gender
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  WHERE v.patient_id = :patient_id
                  AND v.status = 'administered'
                  ORDER BY v.dose_number ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllVaccinations($limit = 10, $offset = 0) {
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, u.full_name as administered_by_name
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  LEFT JOIN users u ON v.administered_by = u.id
                  ORDER BY v.administration_date DESC, v.administration_time DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVaccinationsWithFilters($date, $type, $status, $limit = 10, $offset = 0) {
        $conditions = [];
        $params = [];
        
        if (!empty($date)) {
            $conditions[] = "v.administration_date = :date";
            $params[':date'] = $date;
        }
        
        if (!empty($type)) {
            $conditions[] = "v.vaccine_type = :type";
            $params[':type'] = $type;
        }
        
        if (!empty($status)) {
            $conditions[] = "v.status = :status";
            $params[':status'] = $status;
        }
        
        $whereClause = empty($conditions) ? '1' : implode(' AND ', $conditions);
        
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, u.full_name as administered_by_name
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  LEFT JOIN users u ON v.administered_by = u.id
                  WHERE {$whereClause}
                  ORDER BY v.administration_date DESC, v.administration_time DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countWithFilters($date, $type, $status) {
        $conditions = [];
        $params = [];
        
        if (!empty($date)) {
            $conditions[] = "administration_date = :date";
            $params[':date'] = $date;
        }
        
        if (!empty($type)) {
            $conditions[] = "vaccine_type = :type";
            $params[':type'] = $type;
        }
        
        if (!empty($status)) {
            $conditions[] = "status = :status";
            $params[':status'] = $status;
        }
        
        $whereClause = empty($conditions) ? '1' : implode(' AND ', $conditions);
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$whereClause}";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function countAllVaccinations() {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function getPatientUpcomingVaccinations($patientId) {
        $query = "SELECT v.* 
                  FROM {$this->table} v
                  WHERE v.patient_id = :patient_id
                  AND v.vaccine_type = 'anti_rabies'
                  AND v.status = 'scheduled'
                  AND v.administration_date >= CURDATE()
                  ORDER BY v.administration_date ASC, v.dose_number ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVaccinationStats() {
        $query = "SELECT 
                    COUNT(*) as total_vaccinations,
                    SUM(CASE WHEN vaccine_type = 'anti_rabies' THEN 1 ELSE 0 END) as anti_rabies_count,
                    SUM(CASE WHEN vaccine_type = 'tetanus' THEN 1 ELSE 0 END) as tetanus_count,
                    SUM(CASE WHEN vaccine_type = 'immunoglobulin' THEN 1 ELSE 0 END) as immunoglobulin_count,
                    SUM(CASE WHEN administration_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as last_30_days,
                    SUM(CASE WHEN administration_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as last_7_days
                  FROM {$this->table}
                  WHERE status = 'administered'";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
