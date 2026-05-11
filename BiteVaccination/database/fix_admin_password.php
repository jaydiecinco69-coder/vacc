<?php
// Script to fix admin password hash
require_once __DIR__ . '/../config/database.php';

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'bite_vaccination';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generate new password hash
    $plainPassword = 'admin123';
    $newHash = password_hash($plainPassword, PASSWORD_DEFAULT);
    
    // Update the admin account with correct hash
    $sql = "UPDATE users SET password = ? WHERE email = 'admin@bitecare.com'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newHash]);
    
    echo "✅ Admin password updated successfully!\n";
    echo "📧 Email: admin@bitecare.com\n";
    echo "🔑 Password: admin123\n";
    echo "🔐 New Hash: $newHash\n\n";
    
    // Verify the update
    $verifySql = "SELECT password FROM users WHERE email = 'admin@bitecare.com'";
    $verifyStmt = $pdo->prepare($verifySql);
    $verifyStmt->execute();
    $storedHash = $verifyStmt->fetchColumn();
    
    if (password_verify($plainPassword, $storedHash)) {
        echo "✅ Password verification successful! You can now login.\n";
    } else {
        echo "❌ Password verification failed.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error updating password: " . $e->getMessage() . "\n";
}
?>
