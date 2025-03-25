<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set up autoloading with improved path resolution
spl_autoload_register(function ($class) {
    // Convert namespace separators to directory separators
    $class = str_replace('\\', '/', $class);
    
    // Base directory for the namespace
    $baseDir = __DIR__ . '/';
    
    // Include the file if it exists
    $file = $baseDir . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    } else {
        // For debugging
        error_log("File not found: $file for class $class");
    }
    
    return false;
});

// Manually include all required files to ensure they're loaded
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/Models/Customer.php';
require_once __DIR__ . '/src/Models/Loan.php';
require_once __DIR__ . '/src/Controllers/CustomerController.php';
require_once __DIR__ . '/src/Controllers/LoanController.php';

// Determine the action from the URL
$action = isset($_GET['action']) ? $_GET['action'] : 'home';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Route the request to the appropriate controller and method
switch ($action) {
    // Customer routes
    case 'customers':
        $controller = new LoanManagement\Controllers\CustomerController();
        $controller->index();
        break;
    case 'customer_create':
        $controller = new LoanManagement\Controllers\CustomerController();
        $controller->create();
        break;
    case 'customer_view':
        $controller = new LoanManagement\Controllers\CustomerController();
        $controller->view($id);
        break;
    case 'customer_delete':
        $controller = new LoanManagement\Controllers\CustomerController();
        $controller->delete($id);
        break;
        
    // Loan routes
    case 'loans':
        $controller = new LoanManagement\Controllers\LoanController();
        $controller->index();
        break;
    case 'loan_create':
        $controller = new LoanManagement\Controllers\LoanController();
        $controller->create();
        break;
    case 'loan_view':
        $controller = new LoanManagement\Controllers\LoanController();
        $controller->view($id);
        break;
    case 'loan_update':
        $controller = new LoanManagement\Controllers\LoanController();
        $controller->update($id);
        break;
    case 'loan_delete':
        $controller = new LoanManagement\Controllers\LoanController();
        $controller->delete($id);
        break;
    case 'loan_payment':
        $controller = new LoanManagement\Controllers\LoanController();
        $controller->calculatePayment($id);
        break;
    case 'loan_schedule':
        $controller = new LoanManagement\Controllers\LoanController();
        $controller->generateAmortizationSchedule($id);
        break;
        
    // Default home page
    default:
        // Display a simple home page with links to customers and loans
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Loan Management System</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                .container { max-width: 800px; margin: 0 auto; }
                h1 { color: #333; }
                .menu { display: flex; gap: 20px; margin-top: 20px; }
                .menu a { 
                    display: inline-block; 
                    padding: 10px 20px; 
                    background-color: #4CAF50; 
                    color: white; 
                    text-decoration: none; 
                    border-radius: 4px;
                }
                .menu a:hover { background-color: #45a049; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Welcome to Loan Management System</h1>
                <div class="menu">
                    <a href="index.php?action=customers">Manage Customers</a>
                    <a href="index.php?action=loans">Manage Loans</a>
                </div>
            </div>
        </body>
        </html>';
        break;
}