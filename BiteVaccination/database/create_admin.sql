-- Create new admin account with email login
USE bite_vaccination;

-- Insert new admin user (email: admin@bitecare.com, password: admin123)
INSERT INTO users (username, email, password, full_name, role, phone, is_active, created_at) VALUES 
('bitecare_admin', 'admin@bitecare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BiteCare Administrator', 'admin', '09123456789', TRUE, NOW());

-- Note: Password is 'admin123' (hashed with PHP's password_hash function)
-- Email: admin@bitecare.com
-- Role: admin
-- This account can be used to log in with email instead of username
