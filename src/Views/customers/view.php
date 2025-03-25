<!DOCTYPE html>
<html>
<head>
    <title>View Customer - Loan Management System</title>
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
        .customer-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Details</h1>
        
        <a href="index.php?action=customers" class="btn">Back to Customers</a>
        
        <div class="customer-details">
            <div class="detail-row">
                <span class="detail-label">ID:</span>
                <span><?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'N/A'; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span><?php echo htmlspecialchars($this->customer->customer_name ?? 'N/A'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Address:</span>
                <span><?php echo htmlspecialchars($this->customer->customer_address ?? 'N/A'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span><?php echo htmlspecialchars($this->customer->customer_phone ?? 'N/A'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span><?php echo htmlspecialchars($this->customer->customer_email ?? 'N/A'); ?></span>
            </div>
        </div>
    </div>
</body>
</html>