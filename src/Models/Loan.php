<?php
namespace LoanManagement\Models;

class Loan {
    private $conn;
    private $table = "loans";
    
    // Properties
    private $id;
    private $loanNumber;
    private $branchName;
    private $amount;
    private $interestRate;
    private $loanType;
    private $loanDuration;
    private $loanStatus;
    private $loanDate;
    private $customerId;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Method to set loan properties
    public function createLoan($loanNumber, $branchName, $amount, $interestRate, $loanType, $loanDuration, $loanStatus, $loanDate, $customerId) {
        $this->loanNumber = $loanNumber;
        $this->branchName = $branchName;
        $this->amount = $amount;
        $this->interestRate = $interestRate;
        $this->loanType = $loanType;
        $this->loanDuration = $loanDuration;
        $this->loanStatus = $loanStatus;
        $this->loanDate = $loanDate;
        $this->customerId = $customerId;
    }
    
    // Create loan
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                SET 
                    loan_number = :loan_number, 
                    branch_name = :branch_name, 
                    amount = :amount, 
                    interest_rate = :interest_rate,
                    loan_type = :loan_type,
                    loan_duration = :loan_duration,
                    loan_status = :loan_status,
                    loan_date = :loan_date,
                    customer_id = :customer_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->loanNumber = htmlspecialchars(strip_tags($this->loanNumber));
        $this->branchName = htmlspecialchars(strip_tags($this->branchName));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->interestRate = htmlspecialchars(strip_tags($this->interestRate));
        $this->loanType = htmlspecialchars(strip_tags($this->loanType));
        $this->loanDuration = htmlspecialchars(strip_tags($this->loanDuration));
        $this->loanStatus = htmlspecialchars(strip_tags($this->loanStatus));
        $this->loanDate = htmlspecialchars(strip_tags($this->loanDate));
        $this->customerId = htmlspecialchars(strip_tags($this->customerId));

        // Bind parameters
        $stmt->bindParam(":loan_number", $this->loanNumber);
        $stmt->bindParam(":branch_name", $this->branchName);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":interest_rate", $this->interestRate);
        $stmt->bindParam(":loan_type", $this->loanType);
        $stmt->bindParam(":loan_duration", $this->loanDuration);
        $stmt->bindParam(":loan_status", $this->loanStatus);
        $stmt->bindParam(":loan_date", $this->loanDate);
        $stmt->bindParam(":customer_id", $this->customerId);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    // Read all loans
    public function read() {
        $query = "SELECT l.*, c.customer_name 
                FROM " . $this->table . " l
                LEFT JOIN customers c ON l.customer_id = c.id
                ORDER BY l.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Read one loan
    public function readOne($id) {
        $query = "SELECT l.*, c.customer_name 
                FROM " . $this->table . " l
                LEFT JOIN customers c ON l.customer_id = c.id
                WHERE l.id = ?
                LIMIT 0,1";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->loanNumber = $row['loan_number'];
            $this->branchName = $row['branch_name'];
            $this->amount = $row['amount'];
            $this->interestRate = $row['interest_rate'];
            $this->loanType = $row['loan_type'];
            $this->loanDuration = $row['loan_duration'];
            $this->loanStatus = $row['loan_status'];
            $this->loanDate = $row['loan_date'];
            $this->customerId = $row['customer_id'];
            
            return true;
        }
        
        return false;
    }
    
    public function update() {
        $query = "UPDATE " . $this->table . " 
                SET 
                    loan_number = :loan_number, 
                    branch_name = :branch_name, 
                    amount = :amount, 
                    interest_rate = :interest_rate,
                    loan_type = :loan_type,
                    loan_duration = :loan_duration,
                    loan_status = :loan_status,
                    loan_date = :loan_date,
                    customer_id = :customer_id
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->loanNumber = htmlspecialchars(strip_tags($this->loanNumber));
        $this->branchName = htmlspecialchars(strip_tags($this->branchName));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->interestRate = htmlspecialchars(strip_tags($this->interestRate));
        $this->loanType = htmlspecialchars(strip_tags($this->loanType));
        $this->loanDuration = htmlspecialchars(strip_tags($this->loanDuration));
        $this->loanStatus = htmlspecialchars(strip_tags($this->loanStatus));
        $this->customerId = htmlspecialchars(strip_tags($this->customerId));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":loan_number", $this->loanNumber);
        $stmt->bindParam(":branch_name", $this->branchName);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":interest_rate", $this->interestRate);
        $stmt->bindParam(":loan_type", $this->loanType);
        $stmt->bindParam(":loan_duration", $this->loanDuration);
        $stmt->bindParam(":loan_status", $this->loanStatus);
        $stmt->bindParam(":loan_date", $this->loanDate);
        $stmt->bindParam(":customer_id", $this->customerId);
        $stmt->bindParam(":id", $this->id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    // Getter and setter methods
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getLoanNumber() {
        return $this->loanNumber;
    }
    
    public function setLoanNumber($loanNumber) {
        $this->loanNumber = $loanNumber;
    }
    
    public function getBranchName() {
        return $this->branchName;
    }
    
    public function setBranchName($branchName) {
        $this->branchName = $branchName;
    }
    
    public function getAmount() {
        return $this->amount;
    }
    
    public function setAmount($amount) {
        $this->amount = $amount;
    }
    
    public function getInterestRate() {
        return $this->interestRate;
    }
    
    public function setInterestRate($interestRate) {
        $this->interestRate = $interestRate;
    }
    
    public function getLoanType() {
        return $this->loanType;
    }
    
    public function setLoanType($loanType) {
        $this->loanType = $loanType;
    }
    
    public function getLoanDuration() {
        return $this->loanDuration;
    }
    
    public function setLoanDuration($loanDuration) {
        $this->loanDuration = $loanDuration;
    }
    
    public function getLoanStatus() {
        return $this->loanStatus;
    }
    
    public function setLoanStatus($loanStatus) {
        $this->loanStatus = $loanStatus;
    }
    
    public function getLoanDate() {
        return $this->loanDate;
    }
    
    public function setLoanDate($loanDate) {
        $this->loanDate = $loanDate;
    }
    
    public function getCustomerId() {
        return $this->customerId;
    }
    
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }
}