<!DOCTYPE html>
<html>
<head>
    <title>Challan Form</title>
    <style>
        /* Style the form */
        .challan-container {
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
    <div class="challan-container">
        <div class="header">
            <h2>Fee Challan</h2>
            <p>Student: <?php echo e($installment->fee->student->full_name); ?></p>
            
            <p>Installment: <?php echo e($installment->amount); ?></p>
            <p>Due Date: <?php echo e($installment->due_date); ?></p>
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
                    <td>Installment Amount</td>
                    <td><?php echo e($installment->amount); ?></td>
                </tr>
                <tr>
                    <td>Due Date</td>
                    <td><?php echo e($installment->due_date); ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="footer">
            <p>Payment Method: Bank Transfer / Cash</p>
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

<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/challan.blade.php ENDPATH**/ ?>