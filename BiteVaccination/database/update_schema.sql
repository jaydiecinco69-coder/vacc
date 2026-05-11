-- Update patients table to allow NULL values for optional fields
-- This makes birth_date, gender, and address optional since registration form doesn't collect them

USE bite_vaccination;

-- Modify patients table
ALTER TABLE patients 
MODIFY COLUMN birth_date DATE NULL,
MODIFY COLUMN gender ENUM('male', 'female', 'other') NULL,
MODIFY COLUMN address TEXT NULL;

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_patients_user_id ON patients(user_id);
CREATE INDEX IF NOT EXISTS idx_patients_email ON patients(email);
