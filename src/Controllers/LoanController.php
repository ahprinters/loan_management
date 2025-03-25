<?php
namespace LoanManagement\Controllers;

use LoanManagement\Models\Loan;
use LoanManagement\Models\Customer;
use LoanManagement\Config\Database;

class LoanController {
    private $db;
    private $loan;
    private $customer;
    
    public function __construct() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->loan = new Loan($this->db);
        $this->customer = new Customer($this->db);
    }
    
    public function index() {
        $stmt = $this->loan->read();
        $loans = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        include_once __DIR__ . '/../Views/loans/index.php';
    }
    
    public function create() {
        // Get all customers for the dropdown
        $stmt = $this->customer->read();
        $customers = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loan->createLoan(
                $_POST['loan_number'],
                $_POST['branch_name'],
                $_POST['amount'],
                $_POST['interest_rate'],
                $_POST['loan_type'],
                $_POST['loan_duration'],
                $_POST['loan_status'],
                $_POST['loan_date'],
                $_POST['customer_id']
            );
            
            if($this->loan->create()) {
                $_SESSION['success'] = "Loan created successfully.";
                header('Location: index.php?action=loans');
                exit;
            } else {
                $error = "Unable to create loan.";
            }
        }
        
        include_once __DIR__ . '/../Views/loans/create.php';
    }
    
    public function view($id) {
        if($this->loan->readOne($id)) {
            include_once __DIR__ . '/../Views/loans/view.php';
        } else {
            $_SESSION['error'] = "Loan not found.";
            header('Location: index.php?action=loans');
            exit;
        }
    }
    
    public function update($id) {
        // Get all customers for the dropdown
        $stmt = $this->customer->read();
        $customers = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Get the loan data
        if(!$this->loan->readOne($id)) {
            $_SESSION['error'] = "Loan not found.";
            header('Location: index.php?action=loans');
            exit;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loan->setLoanNumber($_POST['loan_number']);
            $this->loan->setBranchName($_POST['branch_name']);
            $this->loan->setAmount($_POST['amount']);
            $this->loan->setInterestRate($_POST['interest_rate']);
            $this->loan->setLoanType($_POST['loan_type']);
            $this->loan->setLoanDuration($_POST['loan_duration']);
            $this->loan->setLoanStatus($_POST['loan_status']);
            $this->loan->setLoanDate($_POST['loan_date']);
            $this->loan->setCustomerId($_POST['customer_id']);
            
            if($this->loan->update()) {
                $_SESSION['success'] = "Loan updated successfully.";
                header('Location: index.php?action=loans');
                exit;
            } else {
                $error = "Unable to update loan.";
            }
        }
        
        include_once __DIR__ . '/../Views/loans/update.php';
    }
    
    public function delete($id) {
        // Get the loan data
        if(!$this->loan->readOne($id)) {
            $_SESSION['error'] = "Loan not found.";
            header('Location: index.php?action=loans');
            exit;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($this->loan->delete()) {
                $_SESSION['success'] = "Loan deleted successfully.";
                header('Location: index.php?action=loans');
                exit;
            } else {
                $error = "Unable to delete loan.";
            }
        }
        
        include_once __DIR__ . '/../Views/loans/delete.php';
    }
    
    public function calculatePayment($id) {
        // Get the loan data
        if(!$this->loan->readOne($id)) {
            $_SESSION['error'] = "Loan not found.";
            header('Location: index.php?action=loans');
            exit;
        }
        
        $amount = $this->loan->getAmount();
        $interestRate = $this->loan->getInterestRate();
        $loanDuration = $this->loan->getLoanDuration();
        
        // Calculate monthly payment using the formula: P = (A * r * (1 + r)^n) / ((1 + r)^n - 1)
        // Where P is the monthly payment, A is the loan amount, r is the monthly interest rate, and n is the number of months
        $monthlyInterestRate = ($interestRate / 100) / 12;
        $numberOfPayments = $loanDuration * 12;
        
        $monthlyPayment = ($amount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfPayments)) / 
                          (pow(1 + $monthlyInterestRate, $numberOfPayments) - 1);
        
        $totalPayment = $monthlyPayment * $numberOfPayments;
        $totalInterest = $totalPayment - $amount;
        
        $paymentDetails = [
            'monthly_payment' => round($monthlyPayment, 2),
            'total_payment' => round($totalPayment, 2),
            'total_interest' => round($totalInterest, 2)
        ];
        
        include_once __DIR__ . '/../Views/loans/payment_details.php';
    }
    
    public function generateAmortizationSchedule($id) {
        // Get the loan data
        if(!$this->loan->readOne($id)) {
            $_SESSION['error'] = "Loan not found.";
            header('Location: index.php?action=loans');
            exit;
        }
        
        $amount = $this->loan->getAmount();
        $interestRate = $this->loan->getInterestRate();
        $loanDuration = $this->loan->getLoanDuration();
        $loanDate = new \DateTime($this->loan->getLoanDate());
        
        // Calculate monthly payment
        $monthlyInterestRate = ($interestRate / 100) / 12;
        $numberOfPayments = $loanDuration * 12;
        
        $monthlyPayment = ($amount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfPayments)) / 
                          (pow(1 + $monthlyInterestRate, $numberOfPayments) - 1);
        
        // Generate amortization schedule
        $schedule = [];
        $balance = $amount;
        
        for($i = 1; $i <= $numberOfPayments; $i++) {
            $interestPayment = $balance * $monthlyInterestRate;
            $principalPayment = $monthlyPayment - $interestPayment;
            $balance -= $principalPayment;
            
            // Calculate payment date
            $paymentDate = clone $loanDate;
            $paymentDate->modify("+$i months");
            
            $schedule[] = [
                'payment_number' => $i,
                'payment_date' => $paymentDate->format('Y-m-d'),
                'payment_amount' => round($monthlyPayment, 2),
                'principal' => round($principalPayment, 2),
                'interest' => round($interestPayment, 2),
                'balance' => round($balance, 2)
            ];
        }
        
        include_once __DIR__ . '/../Views/loans/amortization_schedule.php';
    }
}