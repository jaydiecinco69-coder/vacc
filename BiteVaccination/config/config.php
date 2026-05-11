<?php
define('BASE_URL', 'http://localhost/BiteVaccination/');
define('APP_NAME', 'Animal Bite Center Management System');
define('APP_VERSION', '1.0.0');

// Set session settings BEFORE starting session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
$secureFlag = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
ini_set('session.cookie_secure', $secureFlag ? 1 : 0);
ini_set('session.cookie_samesite', 'Strict');

// Start session
session_start();

// Timezone
date_default_timezone_set('Asia/Manila');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// User roles
define('ROLE_ADMIN', 'admin');
define('ROLE_RECEPTIONIST', 'receptionist');
define('ROLE_PATIENT', 'patient');

// Vaccination schedule
define('VACCINATION_DAYS', [0, 3, 7, 14]); // Days after bite

// Email settings (for notifications)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('FROM_EMAIL', 'noreply@bitecenter.com');
define('FROM_NAME', 'Animal Bite Center');
?>
