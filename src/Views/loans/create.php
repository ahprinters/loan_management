<!DOCTYPE html>
<html>
<head>
    <title>Add Loan - Loan Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .btn:hover { background-color: #45a049; }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Loan</h1>
        
        <?php if(isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <a href="index.php?action=loans" class="btn">Back to Loans</a>
        
        <form method="post">
            <div class="form-group">
                <label for="loan_number">Loan Number:</label>
                <input type="text" id="loan_number" name="loan_number" required>
            </div>
            
            <div class="form-group">
                <label for="branch_name">Branch Name:</label>
                <input type="text" id="branch_name" name="branch_name" required>
            </div>
            
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" id="interest_rate" name="interest_rate" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="loan_type">Loan Type:</label>
                <select id="loan_type" name="loan_type" required>
                    <option value="">Select Loan Type</option>
                    <option value="Personal">Personal</option>
                    <option value="Home">Home</option>
                    <option value="Auto">Auto</option>
                    <option value="Business">Business</option>
                    <option value="Education">Education</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="loan_duration">Loan Duration (months):</label>
                <input type="number" id="loan_duration" name="loan_duration" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="loan_status">Loan Status:</label>
                <select id="loan_status" name="loan_status" required>
                    <option value="">Select Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Active">Active</option>
                    <option value="Closed">Closed</option>
                    <option value="Defaulted">Defaulted</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="loan_date">Loan Date:</label>
                <input type="date" id="loan_date" name="loan_date" required>
            </div>
            
            <div class="form-group">
                <label for="customer_id">Customer:</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="">Select Customer</option>
                    <?php foreach($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>"><?php echo $customer['customer_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn">Save Loan</button>
        </form>
    </div>
</body>
</html>