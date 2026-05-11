<?php
require_once __DIR__ . '/config/config.php';

// Autoload controllers and models
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
        __DIR__ . '/core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Simple routing
$request = $_SERVER['REQUEST_URI'];

// Remove the base path and query parameters (case-insensitive)
$basePath = '/bitevaccination/';
$requestLower = strtolower($request);

if (stripos($requestLower, $basePath) === 0) {
    $request = substr($request, strlen($basePath));
} else if (stripos($requestLower, '/bitevaccination') === 0) {
    $request = substr($request, strlen('/bitevaccination'));
}

// Remove leading slash, trailing slash and query parameters
$request = ltrim($request, '/');
$request = rtrim($request, '/');
$request = explode('?', $request)[0]; // Remove query parameters

// Set default request if empty
if (empty($request)) {
    $request = 'home';
}

// Route mapping
$routes = [
    'home' => 'HomeController@index',
    'auth/login' => 'AuthController@login',
    'auth/register' => 'AuthController@register',
    'auth/logout' => 'AuthController@logout',
    'auth/forgot-password' => 'AuthController@forgotPassword',
    'auth/reset-password' => 'AuthController@resetPassword',
    'auth/change-password' => 'AuthController@changePassword',
    'dashboard' => 'DashboardController@index',
    'dashboard/edit' => 'DashboardController@editProfile',
    'patients' => 'PatientController@index',
    'patients/create' => 'PatientController@create',
    'patients/edit' => 'PatientController@edit',
    'patients/view' => 'PatientController@show',
    'appointments' => 'AppointmentController@index',
    'appointments/create' => 'AppointmentController@create',
    'appointments/edit' => 'AppointmentController@edit',
    'appointments/updateStatus' => 'AppointmentController@updateStatus',
    'my-appointments' => 'AppointmentController@myAppointments',
    'vaccinations' => 'VaccinationController@index',
    'vaccinations/create' => 'VaccinationController@create',
    'vaccinations/updateStatus' => 'VaccinationController@updateStatus',
    'vaccinations/timeline' => 'VaccinationController@timeline',
    'vaccinations/myVaccinations' => 'VaccinationController@myVaccinations',
    'vaccinations/schedule' => 'VaccinationController@schedule',
    'vaccinations/create-schedule' => 'VaccinationController@createSchedule',
    'vaccinations/storeSchedule' => 'VaccinationController@storeSchedule',
    'vaccinations/patientSchedule' => 'VaccinationController@patientSchedule',
    'reports' => 'ReportController@index',
    'users' => 'UserController@index',
    'users/create' => 'UserController@create',
    'notifications' => 'NotificationController@index',
];

// Handle 404
if (!isset($routes[$request])) {
    http_response_code(404);
    echo '<h1>404 - Page Not Found</h1>';
    echo '<p>The requested page was not found.</p>';
    echo '<p><strong>Debug Info:</strong></p>';
    echo '<p>Request URI: ' . $_SERVER['REQUEST_URI'] . '</p>';
    echo '<p>Parsed Request: ' . $request . '</p>';
    echo '<p>Available Routes: ' . implode(', ', array_keys($routes)) . '</p>';
    exit;
}

// Parse route
list($controllerName, $methodName) = explode('@', $routes[$request]);

// Check if controller exists
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo '<h1>404 - Controller Not Found</h1>';
    exit;
}

// Load controller and call method
require_once $controllerFile;
$controller = new $controllerName();

if (method_exists($controller, $methodName)) {
    $controller->$methodName();
} else {
    http_response_code(404);
    echo '<h1>404 - Method Not Found</h1>';
}
?>
