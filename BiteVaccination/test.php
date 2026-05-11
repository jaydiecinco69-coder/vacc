<?php
// Simple test to verify system components
echo "<h1>System Test</h1>";

// Test config
if (file_exists(__DIR__ . '/config/config.php')) {
    echo "<p>✅ Config file exists</p>";
    require_once __DIR__ . '/config/config.php';
    echo "<p>✅ Config loaded successfully</p>";
} else {
    echo "<p>❌ Config file missing</p>";
}

// Test database connection
try {
    require_once __DIR__ . '/config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    if ($db) {
        echo "<p>✅ Database connection successful</p>";
    } else {
        echo "<p>❌ Database connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test core files
$core_files = ['Controller.php', 'Model.php'];
foreach ($core_files as $file) {
    if (file_exists(__DIR__ . '/core/' . $file)) {
        echo "<p>✅ Core file exists: $file</p>";
    } else {
        echo "<p>❌ Core file missing: $file</p>";
    }
}

// Test controllers
$controllers = ['HomeController.php', 'AuthController.php'];
foreach ($controllers as $file) {
    if (file_exists(__DIR__ . '/controllers/' . $file)) {
        echo "<p>✅ Controller exists: $file</p>";
    } else {
        echo "<p>❌ Controller missing: $file</p>";
    }
}

// Test views
if (file_exists(__DIR__ . '/views/home/index.php')) {
    echo "<p>✅ Home view exists</p>";
} else {
    echo "<p>❌ Home view missing</p>";
}

echo "<h2>PHP Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "</p>";
echo "<p>Current Directory: " . __DIR__ . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</p>";
?>
