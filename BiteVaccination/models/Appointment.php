<?php
class Appointment extends Model {
    protected $table = 'appointments';
    
    public function create($data) {
        return parent::create($data);
    }
    
    public function getTodayAppointments() {
        $query = "SELECT a.*, p.first_name, p.last_name, p.patient_id, u.full_name as staff_name
                  FROM {$this->table} a
                  LEFT JOIN patients p ON a.patient_id = p.id
                  LEFT JOIN users u ON a.staff_id = u.id
                  WHERE a.appointment_date = CURDATE()
                  ORDER BY a.appointment_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUpcoming($limit = 10) {
        $query = "SELECT a.*, p.first_name, p.last_name, p.patient_id
                  FROM {$this->table} a
                  LEFT JOIN patients p ON a.patient_id = p.id
                  WHERE a.appointment_date >= CURDATE()
                  AND a.status IN ('scheduled', 'confirmed')
                  ORDER BY a.appointment_date ASC, a.appointment_time ASC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPatientQueue() {
        $query = "SELECT a.*, p.first_name, p.last_name, p.patient_id
                  FROM {$this->table} a
                  LEFT JOIN patients p ON a.patient_id = p.id
                  WHERE a.appointment_date = CURDATE()
                  AND a.status IN ('confirmed', 'in_progress')
                  ORDER BY a.appointment_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPendingAppointments() {
        $query = "SELECT a.*, p.first_name, p.last_name, p.patient_id
                  FROM {$this->table} a
                  LEFT JOIN patients p ON a.patient_id = p.id
                  WHERE a.status = 'scheduled'
                  ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAppointmentsWithFilters($date, $status, $limit, $offset, $patientId = 0) {
        $conditions = [];
        $params = [];
        
        if (!empty($date)) {
            $conditions[] = "a.appointment_date = :date";
            $params[':date'] = $date;
        } else {
            $conditions[] = "a.appointment_date >= CURDATE()";
        }
        
        if (!empty($status)) {
            $conditions[] = "a.status = :status";
            $params[':status'] = $status;
        }
        
        if ($patientId > 0) {
            $conditions[] = "a.patient_id = :patient_id";
            $params[':patient_id'] = $patientId;
        }
        
        $whereClause = implode(' AND ', $conditions);
        $query = "SELECT a.*, p.first_name, p.last_name, p.patient_id, u.full_name as staff_name
                  FROM {$this->table} a
                  LEFT JOIN patients p ON a.patient_id = p.id
                  LEFT JOIN users u ON a.staff_id = u.id
                  WHERE {$whereClause}
                  ORDER BY a.appointment_date ASC, a.appointment_time ASC
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
    
    public function countWithFilters($date, $status, $patientId = 0) {
        $conditions = [];
        $params = [];
        
        if (!empty($date)) {
            $conditions[] = "appointment_date = :date";
            $params[':date'] = $date;
        } else {
            $conditions[] = "appointment_date >= CURDATE()";
        }
        
        if (!empty($status)) {
            $conditions[] = "status = :status";
            $params[':status'] = $status;
        }
        
        if ($patientId > 0) {
            $conditions[] = "patient_id = :patient_id";
            $params[':patient_id'] = $patientId;
        }
        
        $whereClause = implode(' AND ', $conditions);
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$whereClause}";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    public function getPatientAppointments($patientId) {
        $query = "SELECT a.*, u.full_name as staff_name
                  FROM {$this->table} a
                  LEFT JOIN users u ON a.staff_id = u.id
                  WHERE a.patient_id = :patient_id
                  AND a.appointment_date >= CURDATE()
                  ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPatientAppointmentsWithFilters($patientId, $date = '', $status = '') {
        $conditions = [];
        $params = [];
        
        if ($patientId > 0) {
            $conditions[] = "a.patient_id = :patient_id";
            $params[':patient_id'] = $patientId;
        }
        
        if (!empty($date)) {
            $conditions[] = "DATE(a.appointment_date) = :date";
            $params[':date'] = $date;
        } else {
            $conditions[] = "a.appointment_date >= CURDATE()";
        }
        
        if (!empty($status)) {
            $conditions[] = "a.status = :status";
            $params[':status'] = $status;
        }
        
        $whereClause = implode(' AND ', $conditions);
        $query = "SELECT a.*, u.full_name as staff_name
                  FROM {$this->table} a
                  LEFT JOIN users u ON a.staff_id = u.id
                  WHERE {$whereClause}
                  ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAppointmentsByDateRange($startDate, $endDate) {
        $query = "SELECT a.*, p.first_name, p.last_name, p.patient_id, u.full_name as staff_name
                  FROM {$this->table} a
                  LEFT JOIN patients p ON a.patient_id = p.id
                  LEFT JOIN users u ON a.staff_id = u.id
                  WHERE a.appointment_date BETWEEN :start_date AND :end_date
                  ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAvailableSlots($date) {
        $query = "SELECT appointment_time 
                  FROM {$this->table} 
                  WHERE appointment_date = :date 
                  AND status NOT IN ('cancelled', 'no_show')
                  ORDER BY appointment_time";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        
        $bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $allSlots = $this->generateTimeSlots();
        
        return array_diff($allSlots, $bookedSlots);
    }

    public function isTimeSlotAvailable($date, $time) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                  WHERE appointment_date = :date 
                  AND appointment_time = :time 
                  AND status NOT IN ('cancelled', 'no_show')";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }
    
    private function generateTimeSlots() {
        $slots = [];
        $start = new DateTime('08:00');
        $end = new DateTime('17:00');
        $interval = new DateInterval('PT30M');
        
        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->add($interval);
        }
        
        return $slots;
    }
    
    public function updateStatus($appointmentId, $status) {
        $query = "UPDATE {$this->table} SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $appointmentId);
        
        return $stmt->execute();
    }
    
    public function getAppointmentStats() {
        $query = "SELECT 
                    COUNT(*) as total_appointments,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as no_show,
                    SUM(CASE WHEN appointment_date = CURDATE() THEN 1 ELSE 0 END) as today_appointments
                  FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getDailyStats($days = 7) {
        $query = "SELECT 
                    DATE(appointment_date) as date,
                    COUNT(*) as appointments,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                  FROM {$this->table}
                  WHERE appointment_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                  GROUP BY DATE(appointment_date)
                  ORDER BY date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
