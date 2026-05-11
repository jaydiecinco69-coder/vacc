<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bite_vaccination';

echo "Starting database schema updates...\n";

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Set error mode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully\n";
    
    // Execute ALTER statements one by one
    $sql1 = "ALTER TABLE patients MODIFY COLUMN birth_date DATE NULL";
    echo "Executing: $sql1\n";
    $conn->exec($sql1);
    echo "Success: $sql1\n";
    
    $sql2 = "ALTER TABLE patients MODIFY COLUMN gender ENUM('male', 'female', 'other') NULL";
    echo "Executing: $sql2\n";
    $conn->exec($sql2);
    echo "Success: $sql2\n";
    
    $sql3 = "ALTER TABLE patients MODIFY COLUMN address TEXT NULL";
    echo "Executing: $sql3\n";
    $conn->exec($sql3);
    echo "Success: $sql3\n";
    
    echo "All database updates completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
