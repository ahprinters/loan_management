<?php
namespace LoanManagement\Controllers;

use LoanManagement\Models\Customer;
use LoanManagement\Config\Database;


class CustomerController {
    private $db;
    private $customer;
    
    public function __construct() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->customer = new Customer($this->db);
    }
    
    public function index() {
        $stmt = $this->customer->read();
        $customers = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        include_once __DIR__ . '/../Views/customers/index.php';
    }
    
    public function create() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->customer->createCustomer(
                $_POST['customer_name'],
                $_POST['customer_address'],
                $_POST['customer_phone'],
                $_POST['customer_email']
            );
            
            if($this->customer->create()) {
                $_SESSION['success'] = "Customer created successfully.";
                header('Location: index.php?action=customers');
                exit;
            } else {
                $error = "Unable to create customer.";
            }
        }
        
        include_once __DIR__ . '/../Views/customers/create.php';
    }
    
    public function view($id) {
        if($this->customer->readOne($id)) {
            include_once __DIR__ . '/../Views/customers/view.php';
        } else {
            $_SESSION['error'] = "Customer not found.";
            header('Location: index.php?action=customers');
            exit;
        }
    }
    
    public function update($id) {
        // Use the existing customer object that already has the DB connection
        $this->customer->id = $id;
        
        // If form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input data
            if (empty($_POST['customer_name']) || empty($_POST['customer_address']) || 
                empty($_POST['customer_phone']) || empty($_POST['customer_email'])) {
                $error = "All fields are required";
            } else {
                // Set customer property values directly
                $this->customer->customer_name = htmlspecialchars(trim($_POST['customer_name']));
                $this->customer->customer_address = htmlspecialchars(trim($_POST['customer_address']));
                $this->customer->customer_phone = htmlspecialchars(trim($_POST['customer_phone']));
                $this->customer->customer_email = htmlspecialchars(trim($_POST['customer_email']));
                
                // Update the customer
                if ($this->customer->update()) {
                    $_SESSION['success'] = "Customer updated successfully";
                    header("Location: index.php?action=customers");
                    exit;
                } else {
                    $error = "Unable to update customer";
                }
            }
        }
        
        // Get customer data
        $this->customer->readOne($id);
        
        // Convert to array for the view with fallback values
        $customer = [
            'id' => $this->customer->id ?? '',
            'customer_name' => $this->customer->customer_name ?? '',
            'customer_address' => $this->customer->customer_address ?? '',
            'customer_phone' => $this->customer->customer_phone ?? '',
            'customer_email' => $this->customer->customer_email ?? ''
        ];
        
        // Include the view
        include_once __DIR__ . '/../Views/customers/update.php';
    }
    
    public function delete($id) {
        // Set customer ID
        $this->customer->id = $id;
        
        // Delete the customer
        if ($this->customer->delete()) {
            $_SESSION['success'] = "Customer deleted successfully";
        } else {
            $_SESSION['error'] = "Unable to delete customer";
        }
        
        // Redirect to customers list
        header("Location: index.php?action=customers");
        exit;
    }
}