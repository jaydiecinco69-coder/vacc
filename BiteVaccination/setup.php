<?php
/**
 * Database Setup Script
 * Run this once to set up the database and tables
 */

$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop existing database if it exists
    $pdo->exec("DROP DATABASE IF EXISTS bite_vaccination");
    
    // Read and execute the SQL file
    $sqlFile = __DIR__ . '/database/create_tables.sql';
    
    if (!file_exists($sqlFile)) {
        die('<h2 style="color: red;">SQL file not found at: ' . htmlspecialchars($sqlFile) . '</h2>');
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split by semicolon and execute each statement
    $lines = file($sqlFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $statement = '';
    
    foreach ($lines as $line) {
        // Skip comments
        $line = trim($line);
        if (empty($line) || strpos($line, '--') === 0) {
            continue;
        }
        
        $statement .= ' ' . $line;
        
        // Check if statement ends with semicolon
        if (substr(trim($line), -1) === ';') {
            $pdo->exec($statement);
            $statement = '';
        }
    }
    
    echo "<h2 style='color: green;'>✓ Database Setup Complete!</h2>";
    echo "<p style='font-size: 16px;'>Database and tables created successfully.</p>";
    echo "<p><strong>Default Login Credentials:</strong></p>";
    echo "<ul style='font-size: 14px;'>";
    echo "<li>Username: <code>admin</code></li>";
    echo "<li>Password: <code>admin123</code></li>";
    echo "</ul>";
    echo "<p><a href='http://localhost/bitevaccination/auth/login' style='background: #0066cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Go to Login</a></p>";
    
} catch(PDOException $e) {
    echo "<h2 style='color: red;'>✗ Setup Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p style='color: #666; font-size: 12px;'>Make sure MySQL is running and the credentials are correct.</p>";
}
?>
