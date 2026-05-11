-- Create database
CREATE DATABASE IF NOT EXISTS bite_vaccination;
USE bite_vaccination;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'staff', 'receptionist', 'patient') NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    profile_picture VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Patients table
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    patient_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    birth_date DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address TEXT NULL,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    blood_type VARCHAR(10),
    allergies TEXT,
    medical_history TEXT,
    qr_code VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Animal bite details table
CREATE TABLE animal_bites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    bite_date DATE NOT NULL,
    bite_time TIME NOT NULL,
    animal_type ENUM('dog', 'cat', 'rat', 'other') NOT NULL,
    animal_status ENUM('stray', 'owned', 'unknown') NOT NULL,
    bite_location VARCHAR(255) NOT NULL,
    bite_type ENUM('scratch', 'lick', 'bite', 'other') NOT NULL,
    wound_type ENUM('superficial', 'deep', 'puncture', 'avulsion') NOT NULL,
    body_part VARCHAR(100) NOT NULL,
    animal_description TEXT,
    animal_vaccination_status ENUM('vaccinated', 'unvaccinated', 'unknown'),
    exposure_type ENUM('category_i', 'category_ii', 'category_iii') NOT NULL,
    washing_done BOOLEAN DEFAULT FALSE,
    washing_method VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Appointments table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    staff_id INT,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    appointment_type ENUM('initial_consultation', 'vaccination', 'follow_up', 'emergency') NOT NULL,
    status ENUM('scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT,
    reminder_sent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Vaccinations table
CREATE TABLE vaccinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    appointment_id INT,
    vaccine_type ENUM('anti_rabies', 'tetanus', 'immunoglobulin') NOT NULL,
    vaccine_brand VARCHAR(100),
    batch_number VARCHAR(100),
    dose_number INT NOT NULL,
    administration_date DATE NOT NULL,
    administration_time TIME NOT NULL,
    administered_by INT,
    administration_site ENUM('left_deltoid', 'right_deltoid', 'left_thigh', 'right_thigh') NOT NULL,
    adverse_reactions TEXT,
    next_dose_date DATE,
    status ENUM('scheduled', 'administered', 'missed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    FOREIGN KEY (administered_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Treatment progress table
CREATE TABLE treatment_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    bite_id INT NOT NULL,
    treatment_start_date DATE NOT NULL,
    treatment_end_date DATE,
    treatment_status ENUM('ongoing', 'completed', 'discontinued', 'lost_to_follow_up') DEFAULT 'ongoing',
    total_doses_required INT DEFAULT 5,
    doses_completed INT DEFAULT 0,
    next_appointment_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (bite_id) REFERENCES animal_bites(id) ON DELETE CASCADE
);

-- Notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    patient_id INT,
    appointment_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('appointment_reminder', 'vaccination_reminder', 'missed_appointment', 'system_alert', 'emergency') NOT NULL,
    status ENUM('pending', 'sent', 'read', 'failed') DEFAULT 'pending',
    scheduled_date DATETIME NOT NULL,
    sent_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);

-- Reports table
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_type ENUM('daily', 'weekly', 'monthly', 'annual', 'custom') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    generated_by INT NOT NULL,
    report_data JSON,
    file_path VARCHAR(255),
    date_from DATE,
    date_to DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity logs table
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Vaccine inventory table
CREATE TABLE vaccine_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vaccine_name VARCHAR(100) NOT NULL,
    vaccine_type ENUM('anti_rabies', 'tetanus', 'immunoglobulin') NOT NULL,
    brand VARCHAR(100),
    batch_number VARCHAR(100) UNIQUE NOT NULL,
    quantity_received INT NOT NULL,
    quantity_used INT DEFAULT 0,
    quantity_remaining INT GENERATED ALWAYS AS (quantity_received - quantity_used) STORED,
    expiry_date DATE NOT NULL,
    storage_location VARCHAR(100),
    supplier VARCHAR(100),
    received_date DATE NOT NULL,
    status ENUM('active', 'expired', 'depleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX idx_patients_patient_id ON patients(patient_id);
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_vaccinations_patient ON vaccinations(patient_id);
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_activity_logs_user ON activity_logs(user_id);
CREATE INDEX idx_vaccine_inventory_batch ON vaccine_inventory(batch_number);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@bitecenter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin');

-- Insert sample vaccine inventory
INSERT INTO vaccine_inventory (vaccine_name, vaccine_type, brand, batch_number, quantity_received, expiry_date, storage_location, supplier, received_date) VALUES
('Verorab', 'anti_rabies', 'Sanofi Pasteur', 'VRB2024001', 50, '2025-12-31', 'Refrigerator 1', 'Sanofi', CURDATE()),
('Tetanus Toxoid', 'tetanus', 'BioFarma', 'TT2024001', 100, '2025-06-30', 'Refrigerator 2', 'BioFarma', CURDATE()),
('Human Rabies Immunoglobulin', 'immunoglobulin', 'Bharat Biotech', 'HRIG2024001', 20, '2025-09-30', 'Freezer 1', 'Bharat Biotech', CURDATE());
