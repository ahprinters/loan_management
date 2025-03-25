<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer - Loan Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
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
        input[type="email"],
        textarea {
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
        <h1>Edit Customer</h1>
        
        <?php if(isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <a href="index.php?action=customers" class="btn">Back to Customers</a>
        
        <form method="post">
            <div class="form-group">
                <label for="customer_name">Name:</label>
                <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer['customer_name']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="customer_address">Address:</label>
                <textarea id="customer_address" name="customer_address" required><?php echo $customer['customer_address']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="customer_phone">Phone:</label>
                <input type="text" id="customer_phone" name="customer_phone" value="<?php echo $customer['customer_phone']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="customer_email">Email:</label>
                <input type="email" id="customer_email" name="customer_email" value="<?php echo $customer['customer_email']; ?>" required>
            </div>
            
            <button type="submit" class="btn">Update Customer</button>
        </form>
    </div>
</body>
</html>