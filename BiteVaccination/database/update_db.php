<?php
// Simple database update script
try {
    $conn = new PDO("mysql:host=localhost;dbname=bite_vaccination", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database\n";
    
    // Execute the ALTER commands
    $sql = "ALTER TABLE patients MODIFY COLUMN birth_date DATE NULL, ALTER TABLE patients MODIFY COLUMN gender ENUM('male', 'female', 'other') NULL, ALTER TABLE patients MODIFY COLUMN address TEXT NULL";
    
    if ($conn->exec($sql)) {
        echo "Database schema updated successfully!\n";
    } else {
        echo "Error updating database: " . print_r($conn->errorInfo()) . "\n";
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
