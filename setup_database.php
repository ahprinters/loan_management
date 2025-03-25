<?php
require_once 'config/Database.php';

use LoanManagement\Config\Database;

// Create database connection
$database = new Database();
$conn = $database->getConnection();

// SQL to create customers table
$customersTable = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_address VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// SQL to create loans table
$loansTable = "CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loan_number VARCHAR(50) NOT NULL,
    branch_name VARCHAR(100) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    interest_rate DECIMAL(5, 2) NOT NULL,
    loan_type VARCHAR(50) NOT NULL,
    loan_duration INT NOT NULL,
    loan_status VARCHAR(20) NOT NULL,
    loan_date DATE NOT NULL,
    customer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
)";

// Execute the SQL statements
try {
    $conn->exec($customersTable);
    echo "Customers table created successfully<br>";
    
    $conn->exec($loansTable);
    echo "Loans table created successfully<br>";
    
    echo "Database setup completed successfully!";
} catch(PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}