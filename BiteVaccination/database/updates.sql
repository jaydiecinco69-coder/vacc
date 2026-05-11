-- Update patients table to allow NULL values for optional fields
USE bite_vaccination;

ALTER TABLE patients MODIFY COLUMN birth_date DATE NULL;
ALTER TABLE patients MODIFY COLUMN gender ENUM('male', 'female', 'other') NULL;
ALTER TABLE patients MODIFY COLUMN address TEXT NULL;
