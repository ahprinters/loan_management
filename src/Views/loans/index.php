<!DOCTYPE html>
<html>
<head>
    <title>Loans - Loan Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
        }
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Loans</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="btn">Home</a>
        <a href="index.php?action=loan_create" class="btn">Add New Loan</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Loan Number</th>
                    <th>Branch</th>
                    <th>Amount</th>
                    <th>Interest Rate</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Customer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($loans)): ?>
                    <tr>
                        <td colspan="10">No loans found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($loans as $loan): ?>
                        <tr>
                            <td><?php echo $loan['id']; ?></td>
                            <td><?php echo $loan['loan_number']; ?></td>
                            <td><?php echo $loan['branch_name']; ?></td>
                            <td><?php echo number_format($loan['amount'], 2); ?></td>
                            <td><?php echo $loan['interest_rate']; ?>%</td>
                            <td><?php echo $loan['loan_type']; ?></td>
                            <td><?php echo $loan['loan_duration']; ?> months</td>
                            <td><?php echo $loan['loan_status']; ?></td>
                            <td><?php echo $loan['customer_name']; ?></td>
                            <td class="action-links">
                                <a href="index.php?action=loan_view&id=<?php echo $loan['id']; ?>">View</a>
                                <a href="index.php?action=loan_update&id=<?php echo $loan['id']; ?>">Edit</a>
                                <a href="index.php?action=loan_delete&id=<?php echo $loan['id']; ?>" onclick="return confirm('Are you sure you want to delete this loan?');">Delete</a>
                                <a href="index.php?action=loan_schedule&id=<?php echo $loan['id']; ?>">Schedule</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>