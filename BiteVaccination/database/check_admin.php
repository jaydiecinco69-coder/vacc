<?php
// Script to check if admin account exists
require_once __DIR__ . '/../config/database.php';

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'bite_vaccination';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if admin account exists
    $sql = "SELECT id, username, email, full_name, role, is_active FROM users WHERE email = ? OR role = 'admin' ORDER BY id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['admin@bitecare.com']);
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "🔍 Checking admin accounts in database...\n\n";
    
    if (empty($users)) {
        echo "❌ No admin accounts found with email 'admin@bitecare.com'\n";
    } else {
        echo "✅ Found " . count($users) . " admin account(s):\n\n";
        
        foreach ($users as $user) {
            echo "📋 User ID: " . $user['id'] . "\n";
            echo "👤 Username: " . $user['username'] . "\n";
            echo "📧 Email: " . $user['email'] . "\n";
            echo "👨‍💼 Full Name: " . $user['full_name'] . "\n";
            echo "🔐 Role: " . $user['role'] . "\n";
            echo "✅ Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
            echo "─────────────────────────────────\n";
        }
    }
    
    // Test password verification
    echo "\n🔐 Testing password verification...\n";
    $testPassword = 'admin123';
    $testSql = "SELECT password FROM users WHERE email = ?";
    $testStmt = $pdo->prepare($testSql);
    $testStmt->execute(['admin@bitecare.com']);
    $storedHash = $testStmt->fetchColumn();
    
    if ($storedHash && password_verify($testPassword, $storedHash)) {
        echo "✅ Password 'admin123' matches stored hash\n";
    } else {
        echo "❌ Password 'admin123' does NOT match stored hash\n";
        echo "🔑 Stored hash: " . $storedHash . "\n";
        echo "🔑 Test hash: " . password_hash($testPassword, PASSWORD_DEFAULT) . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
