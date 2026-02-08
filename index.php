<?php

// Start Session
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    // Prefix for our namespace
    $prefix = 'App\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/../app/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // moved to next registered autoloader
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

// Define Routes
$router = new Router();

// Routes definition
$router->add('GET', '', 'HomeController', 'index');
$router->add('GET', 'home', 'HomeController', 'index');
$router->add('POST', 'contact/send', 'HomeController', 'sendContact');

// Auth Routes
$router->add('GET', 'login', 'AuthController', 'loginForm');
$router->add('POST', 'login', 'AuthController', 'login');
$router->add('GET', 'register', 'AuthController', 'registerForm');
$router->add('POST', 'register', 'AuthController', 'register');
$router->add('GET', 'logout', 'AuthController', 'logout');

// Public Routes
$router->add('GET', 'doctors', 'DoctorController', 'index');
$router->add('GET', 'doctors/details', 'DoctorController', 'details');
$router->add('GET', 'services', 'HomeController', 'services');

// Booking Routes
$router->add('GET', 'book', 'BookingController', 'index');
$router->add('GET', 'book/slots', 'BookingController', 'getSlots');
$router->add('POST', 'book/confirm', 'BookingController', 'confirm');

// Dashboard Routes
$router->add('GET', 'dashboard', 'DashboardController', 'index');
$router->add('GET', 'dashboard/availability', 'DoctorController', 'manageAvailability');
$router->add('POST', 'dashboard/availability', 'DoctorController', 'manageAvailability');
$router->add('GET', 'dashboard/users', 'DashboardController', 'users');
$router->add('GET', 'dashboard/doctors', 'DashboardController', 'doctors');
$router->add('GET', 'dashboard/staff', 'DashboardController', 'staff');
$router->add('GET', 'dashboard/feedback', 'DashboardController', 'feedback');
$router->add('POST', 'dashboard/feedback/reply', 'DashboardController', 'replyFeedback');
$router->add('POST', 'dashboard/profile/update', 'DashboardController', 'updateProfile');

// Admin CRUD Routes
$router->add('POST', 'admin/user/delete', 'AdminController', 'deleteUser');
$router->add('GET', 'admin/user/edit', 'AdminController', 'editUserForm');
$router->add('POST', 'admin/user/update', 'AdminController', 'updateUser');
$router->add('GET', 'admin/user/add', 'AdminController', 'addUserForm');
$router->add('POST', 'admin/user/create', 'AdminController', 'createUser');

$router->add('GET', 'admin/doctor/edit', 'AdminController', 'editDoctorForm');
$router->add('POST', 'admin/doctor/update', 'AdminController', 'updateDoctor');
$router->add('GET', 'admin/doctor/add', 'AdminController', 'addDoctorForm');
$router->add('POST', 'admin/doctor/create', 'AdminController', 'createDoctor');

$router->add('GET', 'admin/appointments', 'AdminController', 'appointments');
$router->add('POST', 'admin/appointments/cancel', 'AdminController', 'cancelAppointment');

// Report Routes
$router->add('GET', 'admin/report/monthly', 'ReportController', 'monthly');
$router->add('GET', 'admin/report/export', 'ReportController', 'export');

$router->add('GET', 'admin/specialties', 'AdminController', 'specialties');
$router->add('POST', 'admin/specialty/add', 'AdminController', 'addSpecialty');
$router->add('POST', 'admin/specialty/delete', 'AdminController', 'deleteSpecialty');

$router->add('POST', 'admin/staff/delete', 'AdminController', 'deleteStaff');
$router->add('GET', 'admin/staff/add', 'AdminController', 'addStaffForm');
$router->add('POST', 'admin/staff/create', 'AdminController', 'createStaff');
$router->add('GET', 'admin/staff/edit', 'AdminController', 'editStaffForm');
$router->add('POST', 'admin/staff/update', 'AdminController', 'updateStaff');

// Billing Routes
$router->add('POST', 'billing/generate', 'BillingController', 'generateInvoice');
$router->add('POST', 'billing/process', 'BillingController', 'processPayment');

// Appointment Actions
$router->add('POST', 'appointment/assign_room', 'AppointmentController', 'assign_room');
$router->add('POST', 'appointment/complete', 'AppointmentController', 'complete');
$router->add('POST', 'appointment/upload', 'AppointmentController', 'upload');

// Global Search
$router->add('GET', 'search', 'SearchController', 'index');

// Dispatch
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$uri = parse_url($uri, PHP_URL_PATH);

// Handle subfolder installation if necessary. 
// If project is at /finalist_hospital/public/index.php, we want APPROOT to be /finalist_hospital
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$base = str_replace('\\', '/', $scriptName);
$base = rtrim($base, '/');

// If we are currently in the /public folder in the URL, we might want to strip it for APPROOT consistency
// This helps if the user accesses via localhost/finalist_hospital/ OR localhost/finalist_hospital/public/
if (str_ends_with($base, '/public')) {
    $base = substr($base, 0, -7);
}
define('APPROOT', $base);

// Also try to strip /public from the URI if it's explicitly there, to keep routing clean
if (strpos($uri, APPROOT . '/public') === 0) {
    $uri = substr($uri, strlen(APPROOT . '/public'));
} elseif (strpos($uri, APPROOT) === 0) {
    $uri = substr($uri, strlen(APPROOT));
}

// Remove leading slash
$uri = ltrim($uri, '/');

// Dispatch to router
$router->dispatch($uri, $method);
