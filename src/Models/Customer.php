<?php
namespace LoanManagement\Models;

class Customer {
    private $customerName;
    private $customerAddress;
    private $customerPhoneNumber;
    private $customerEmail;
    private $conn;
    private $table = "customers";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Constructor for creating a customer object without DB
    public function createCustomer($customerName, $customerAddress, $customerPhoneNumber, $customerEmail) {
        $this->customerName = $customerName;
        $this->customerAddress = $customerAddress;
        $this->customerPhoneNumber = $customerPhoneNumber;
        $this->customerEmail = $customerEmail;
    }

    public function getCustomerName() {
        return $this->customerName;
    }

    public function setCustomerName($customerName) {
        $this->customerName = $customerName;
    }

    public function getCustomerAddress() {
        return $this->customerAddress;
    }

    public function setCustomerAddress($customerAddress) {
        $this->customerAddress = $customerAddress;
    }

    public function getCustomerPhoneNumber() {
        return $this->customerPhoneNumber;
    }

    public function setCustomerPhoneNumber($customerPhoneNumber) {
        $this->customerPhoneNumber = $customerPhoneNumber;
    }

    public function getCustomerEmail() {
        return $this->customerEmail;
    }

    public function setCustomerEmail($customerEmail) {
        $this->customerEmail = $customerEmail;
    }

    // Database operations
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                SET 
                    customer_name = :name, 
                    customer_address = :address, 
                    customer_phone = :phone, 
                    customer_email = :email";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->customerName = htmlspecialchars(strip_tags($this->customerName));
        $this->customerAddress = htmlspecialchars(strip_tags($this->customerAddress));
        $this->customerPhoneNumber = htmlspecialchars(strip_tags($this->customerPhoneNumber));
        $this->customerEmail = htmlspecialchars(strip_tags($this->customerEmail));

        // Bind parameters
        $stmt->bindParam(":name", $this->customerName);
        $stmt->bindParam(":address", $this->customerAddress);
        $stmt->bindParam(":phone", $this->customerPhoneNumber);
        $stmt->bindParam(":email", $this->customerEmail);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if($row) {
            $this->customerName = $row['customer_name'];
            $this->customerAddress = $row['customer_address'];
            $this->customerPhoneNumber = $row['customer_phone'];
            $this->customerEmail = $row['customer_email'];
            return true;
        }
        
        return false;
    }
}