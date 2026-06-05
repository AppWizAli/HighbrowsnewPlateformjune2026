<!DOCTYPE html>
<html>
<head>
    <title>Salary Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .receipt-container {
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .footer button {
            padding: 10px 20px;
            background-color: #084298;
            color: white;
            border: none;
            cursor: pointer;
        }
        @media print {
            .print{
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h2>Salary Receipt</h2>
        </div>
        <div class="details">
            <p><strong>Teacher Name:</strong> <?php echo e($salary->teacher->name); ?></p>
            <p><strong>Salary Amount:</strong> <?php echo e($salary->teacher->salary); ?></p>
            <p><strong>Payment Date:</strong> <?php echo e($salary->updated_at->format('d M Y')); ?></p>
            <p><strong>Status:</strong> <?php echo e(ucfirst($salary->status)); ?></p>
        </div>
        <div class="footer">
            <p>Thank you for your service!</p>
            <button onclick="window.print()" class="print">Print</button>
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/salary-receipt.blade.php ENDPATH**/ ?>