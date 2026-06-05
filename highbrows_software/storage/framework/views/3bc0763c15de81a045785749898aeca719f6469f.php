<!DOCTYPE html>
<html>
<head>
    <title>Advance Payment Receipt</title>
    <style>
        .receipt-container {
            width: 100%;
            padding: 20px;
            border: 1px solid #000;
        }
        .header, .footer {
            text-align: center;
        }
        .details-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .details-table th, .details-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h2>Receipt for Advance Payment</h2>
            <p>Student: <?php echo e($fee->student->full_name); ?></p>
            <p>Advance Amount: <?php echo e($fee->advance); ?></p>
            <p>Payment Date: <?php echo e($fee->created_at); ?></p>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Advance Paid</td>
                    <td><?php echo e($fee->advance); ?></td>
                </tr>
                <tr>
                    <td>Total Fee</td>
                    <td><?php echo e($fee->total_fee); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Receipt Number: <?php echo e($fee->id); ?></p>
            
            <p>Address: HighBrows Acadmey</p>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print(); 
        }
    </script>
</body>
</html>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/receipt.blade.php ENDPATH**/ ?>