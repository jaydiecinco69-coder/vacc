<?php
// Script to create new admin account
require_once __DIR__ . '/../config/database.php';

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'bite_vaccination';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Insert new admin user
    $sql = "INSERT INTO users (username, email, password, full_name, role, phone, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    $username = 'bitecare_admin';
    $email = 'admin@bitecare.com';
    $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // admin123
    $fullName = 'BiteCare Administrator';
    $role = 'admin';
    $phone = '09123456789';
    $isActive = 1;
    
    $stmt->execute([$username, $email, $password, $fullName, $role, $phone, $isActive]);
    
    echo "✅ New admin account created successfully!\n";
    echo "📧 Email: admin@bitecare.com\n";
    echo "🔑 Password: admin123\n";
    echo "👤 Role: admin\n";
    
} catch (PDOException $e) {
    echo "❌ Error creating admin account: " . $e->getMessage() . "\n";
}
?>
