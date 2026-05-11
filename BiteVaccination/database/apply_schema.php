<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bite_vaccination';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Set error mode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully\n";
    
    // SQL commands to execute
    $sqlCommands = [
        "ALTER TABLE patients MODIFY COLUMN birth_date DATE NULL",
        "ALTER TABLE patients MODIFY COLUMN gender ENUM('male', 'female', 'other') NULL",
        "ALTER TABLE patients MODIFY COLUMN address TEXT NULL"
    ];
    
    // Execute each command
    foreach ($sqlCommands as $sql) {
        try {
            echo "Executing: $sql\n";
            $conn->exec($sql);
            echo "Success: $sql\n";
        } catch (PDOException $e) {
            echo "Error executing: $sql\n";
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "Database schema updates completed!\n";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
