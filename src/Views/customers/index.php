<!DOCTYPE html>
<html>
<head>
    <title>Customers - Loan Management System</title>
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
        <h1>Customers</h1>
        
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
        <a href="index.php?action=customer_create" class="btn">Add New Customer</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($customers)): ?>
                    <tr>
                        <td colspan="6">No customers found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($customers as $customer): ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td><?php echo $customer['customer_name']; ?></td>
                            <td><?php echo $customer['customer_address']; ?></td>
                            <td><?php echo $customer['customer_phone']; ?></td>
                            <td><?php echo $customer['customer_email']; ?></td>
                            <td class="action-links">
                                <a href="index.php?action=customer_view&id=<?php echo $customer['id']; ?>">View</a>
                                <a href="index.php?action=customer_update&id=<?php echo $customer['id']; ?>">Edit</a>
                                <a href="index.php?action=customer_delete&id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>