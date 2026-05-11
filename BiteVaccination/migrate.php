<?php
/**
 * Database Migration Script
 * Run this to update existing database with missing columns
 */

$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'bite_vaccination';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if user_id column exists in patients table
    $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'patients' AND COLUMN_NAME = 'user_id'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();
    
    if (!$result) {
        // Add user_id column if it doesn't exist
        $pdo->exec("ALTER TABLE patients ADD COLUMN user_id INT AFTER id");
        $pdo->exec("ALTER TABLE patients ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
        
        echo "<h2 style='color: green;'>✓ Migration Complete!</h2>";
        echo "<p>Added user_id column to patients table.</p>";
        echo "<p><a href='http://localhost/bitevaccination/auth/login' style='color: #0066cc; text-decoration: underline;'>Return to Login</a></p>";
    } else {
        echo "<h2 style='color: blue;'>✓ Database Already Updated</h2>";
        echo "<p>The user_id column already exists in patients table.</p>";
        echo "<p><a href='http://localhost/bitevaccination/auth/login' style='color: #0066cc; text-decoration: underline;'>Return to Login</a></p>";
    }
    
} catch(PDOException $e) {
    echo "<h2 style='color: red;'>✗ Migration Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
