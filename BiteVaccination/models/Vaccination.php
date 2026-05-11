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
    
    public function countAllVaccinations() {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
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
        // Build query properly
        if ($administeredBy) {
            $query = "UPDATE {$this->table} SET status = :status, administered_by = :administered_by, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':administered_by', $administeredBy);
            $stmt->bindValue(':id', $vaccinationId);
        } else {
            $query = "UPDATE {$this->table} SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $vaccinationId);
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
    
    public function getPatientVaccinationSchedule($patientId) {
        $query = "SELECT v.*, a.appointment_date, a.appointment_time, a.status as appointment_status,
                        p.first_name, p.last_name, p.patient_id
                  FROM {$this->table} v
                  LEFT JOIN appointments a ON v.appointment_id = a.id
                  LEFT JOIN patients p ON v.patient_id = p.id
                  WHERE v.patient_id = :patient_id
                  AND (a.status IN ('confirmed', 'completed') OR a.status IS NULL)
                  ORDER BY v.administration_date ASC, v.dose_number ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createUpcomingVaccination($data) {
        $query = "INSERT INTO {$this->table} 
                  (patient_id, appointment_id, vaccine_type, vaccine_brand, batch_number, 
                   dose_number, administration_date, administration_time, administration_site, 
                   next_dose_date, status, created_at) 
                  VALUES 
                  (:patient_id, :appointment_id, :vaccine_type, :vaccine_brand, :batch_number,
                   :dose_number, :administration_date, :administration_time, :administration_site,
                   :next_dose_date, 'scheduled', CURRENT_TIMESTAMP)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $data['patient_id']);
        $stmt->bindParam(':appointment_id', $data['appointment_id']);
        $stmt->bindParam(':vaccine_type', $data['vaccine_type']);
        $stmt->bindParam(':vaccine_brand', $data['vaccine_brand']);
        $stmt->bindParam(':batch_number', $data['batch_number']);
        $stmt->bindParam(':dose_number', $data['dose_number']);
        $stmt->bindParam(':administration_date', $data['administration_date']);
        $stmt->bindParam(':administration_time', $data['administration_time']);
        $stmt->bindParam(':administration_site', $data['administration_site']);
        $stmt->bindParam(':next_dose_date', $data['next_dose_date']);
        
        return $stmt->execute();
    }
    
    public function getVaccinationScheduleForConfirmedAppointments() {
        $query = "SELECT v.*, p.first_name, p.last_name, p.patient_id, p.phone,
                        a.appointment_date, a.appointment_time, a.status as appointment_status
                  FROM {$this->table} v
                  LEFT JOIN patients p ON v.patient_id = p.id
                  LEFT JOIN appointments a ON v.appointment_id = a.id
                  WHERE a.status IN ('confirmed', 'completed')
                  OR (v.status = 'scheduled' AND v.appointment_id IS NULL)
                  ORDER BY v.administration_date ASC, v.administration_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPatientsWithConfirmedAppointments() {
        $query = "SELECT DISTINCT p.id, p.first_name, p.last_name, p.patient_id, p.phone,
                        COUNT(v.id) as scheduled_vaccinations,
                        GROUP_CONCAT(DISTINCT a.status) as appointment_statuses
                  FROM patients p
                  INNER JOIN appointments a ON p.id = a.patient_id
                  LEFT JOIN vaccinations v ON p.id = v.patient_id
                  WHERE a.status IN ('scheduled', 'confirmed', 'completed')
                  GROUP BY p.id, p.first_name, p.last_name, p.patient_id, p.phone
                  ORDER BY p.last_name, p.first_name";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllPatientsForScheduling() {
        // First try a simple query to get all patients
        $query = "SELECT p.id, p.first_name, p.last_name, p.patient_id, p.phone
                  FROM patients p 
                  WHERE p.id IS NOT NULL
                  ORDER BY p.last_name, p.first_name";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // If we got patients, add the additional info
            if (!empty($patients)) {
                foreach ($patients as &$patient) {
                    // Get vaccination count
                    $vaccQuery = "SELECT COUNT(*) as count FROM vaccinations WHERE patient_id = :patient_id";
                    $vaccStmt = $this->db->prepare($vaccQuery);
                    $vaccStmt->bindParam(':patient_id', $patient['id']);
                    $vaccStmt->execute();
                    $vaccResult = $vaccStmt->fetch(PDO::FETCH_ASSOC);
                    $patient['scheduled_vaccinations'] = $vaccResult['count'];
                    
                    // Get appointment info
                    $apptQuery = "SELECT MAX(appointment_date) as last_date, GROUP_CONCAT(DISTINCT status) as statuses 
                                 FROM appointments WHERE patient_id = :patient_id";
                    $apptStmt = $this->db->prepare($apptQuery);
                    $apptStmt->bindParam(':patient_id', $patient['id']);
                    $apptStmt->execute();
                    $apptResult = $apptStmt->fetch(PDO::FETCH_ASSOC);
                    $patient['last_appointment_date'] = $apptResult['last_date'];
                    $patient['appointment_statuses'] = $apptResult['statuses'];
                }
            }
            
            return $patients;
        } catch (Exception $e) {
            // Log error and return empty array
            error_log("Error in getAllPatientsForScheduling: " . $e->getMessage());
            return [];
        }
    }
    
    public function testDatabaseConnection() {
        try {
            // Test basic query
            $query = "SELECT COUNT(*) as count FROM patients";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'patient_count' => $result['count'],
                'message' => 'Database connection successful'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Database connection failed'
            ];
        }
    }
    
    public function createFirstDoseFromAppointment($appointmentId) {
        // Get appointment details
        $query = "SELECT a.*, p.first_name, p.last_name, p.patient_id 
                  FROM appointments a 
                  LEFT JOIN patients p ON a.patient_id = p.id 
                  WHERE a.id = :appointment_id AND a.status = 'confirmed'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':appointment_id', $appointmentId);
        $stmt->execute();
        
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$appointment) {
            return false;
        }
        
        // Check if first dose already exists for this appointment
        $checkQuery = "SELECT id FROM {$this->table} 
                       WHERE appointment_id = :appointment_id AND dose_number = 1";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':appointment_id', $appointmentId);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            return false; // First dose already exists
        }
        
        // Determine vaccine type based on appointment type
        $vaccineType = 'anti_rabies'; // Default
        if ($appointment['appointment_type'] === 'initial_consultation') {
            $vaccineType = 'anti_rabies';
        } elseif ($appointment['appointment_type'] === 'follow_up') {
            // Check existing vaccinations to determine next dose
            $doseQuery = "SELECT MAX(dose_number) as max_dose FROM {$this->table} 
                          WHERE patient_id = :patient_id AND vaccine_type = 'anti_rabies'";
            $doseStmt = $this->db->prepare($doseQuery);
            $doseStmt->bindParam(':patient_id', $appointment['patient_id']);
            $doseStmt->execute();
            $result = $doseStmt->fetch(PDO::FETCH_ASSOC);
            $nextDose = ($result['max_dose'] ?? 0) + 1;
        } else {
            $nextDose = 1;
        }
        
        // Create first dose vaccination record
        $insertQuery = "INSERT INTO {$this->table} 
                        (patient_id, appointment_id, vaccine_type, dose_number, 
                         administration_date, administration_time, administration_site, 
                         status, created_at) 
                        VALUES 
                        (:patient_id, :appointment_id, :vaccine_type, :dose_number,
                         :administration_date, :administration_time, :administration_site,
                         'scheduled', CURRENT_TIMESTAMP)";
        
        $stmt = $this->db->prepare($insertQuery);
        $stmt->bindParam(':patient_id', $appointment['patient_id']);
        $stmt->bindParam(':appointment_id', $appointmentId);
        $stmt->bindParam(':vaccine_type', $vaccineType);
        $stmt->bindParam(':dose_number', $nextDose);
        $stmt->bindParam(':administration_date', $appointment['appointment_date']);
        $stmt->bindParam(':administration_time', $appointment['appointment_time']);
        $stmt->bindParam(':administration_site', $site = 'left_deltoid');
        
        if ($stmt->execute()) {
            $vaccinationId = $this->db->lastInsertId();
            
            // If this is anti-rabies dose 1, schedule follow-up doses
            if ($vaccineType === 'anti_rabies' && $nextDose === 1) {
                $this->scheduleAntiRabiesFollowUpDoses([
                    'patient_id' => $appointment['patient_id'],
                    'vaccine_type' => 'anti_rabies',
                    'vaccine_brand' => 'Verorab', // Default brand
                    'batch_number' => 'AUTO' . date('Ym'), // Auto-generated batch
                    'administration_date' => $appointment['appointment_date'],
                    'administration_time' => $appointment['appointment_time'],
                    'administration_site' => $site,
                    'dose_number' => 1
                ]);
            }
            
            return $vaccinationId;
        }
        
        return false;
    }
    
    private function scheduleAntiRabiesFollowUpDoses($firstDose) {
        // Anti-rabies vaccination schedule: Days 0, 3, 7, 14, 28
        $scheduleDays = [0, 3, 7, 14, 28];
        $firstDate = $firstDose['administration_date'];
        $time = $firstDose['administration_time'] ?: '09:00';
        $site = $firstDose['administration_site'];
        $brand = $firstDose['vaccine_brand'];
        $batch = $firstDose['batch_number'];
        $patientId = $firstDose['patient_id'];
        
        foreach ($scheduleDays as $index => $day) {
            if ($index === 0) {
                continue; // Skip first dose (already created)
            }
            
            $doseNumber = $index + 1;
            $scheduledDate = date('Y-m-d', strtotime("+{$day} days", strtotime($firstDate)));
            
            // Check if this dose already exists
            if ($this->getExistingDose($patientId, $doseNumber, 'anti_rabies')) {
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
            
            $this->create($scheduledData);
        }
    }
}
?>
