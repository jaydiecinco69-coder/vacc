<?php
// Simple database update script
echo "Starting database updates...\n";

try {
    // Connect to MySQL
    $conn = new PDO("mysql:host=localhost;dbname=bite_vaccination", "root", "");
    
    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database\n";
    
    // Update patients table
    $sql1 = "ALTER TABLE patients MODIFY COLUMN birth_date DATE NULL";
    echo "Executing: $sql1\n";
    $conn->exec($sql1);
    
    $sql2 = "ALTER TABLE patients MODIFY COLUMN gender ENUM('male', 'female', 'other') NULL";
    echo "Executing: $sql2\n";
    $conn->exec($sql2);
    
    $sql3 = "ALTER TABLE patients MODIFY COLUMN address TEXT NULL";
    echo "Executing: $sql3\n";
    $conn->exec($sql3);
    
    echo "All database updates completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
