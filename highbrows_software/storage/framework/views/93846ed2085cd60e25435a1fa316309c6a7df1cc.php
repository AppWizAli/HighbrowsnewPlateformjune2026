<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Fee Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .receipt-container {
            width: 600px;
            margin: auto;
            border: 2px solid #084298;
            padding: 15px;
        }
        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .sub-header {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
        }
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .fee-table, .fee-table th, .fee-table td {
            border: 1px solid black;
            text-align: left;
            padding: 5px;
        }
        .footer {
            margin-top: 15px;
        }
        .signature {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="header">HIGHBROWS PRE CADET SCHOOL</div>
    <div class="sub-header">
        <b>Main Campus:</b> Sawan, GT Road, Rawalpindi - 0307-5973563 / 051-5921778 <br>
        <b>Sub Campus:</b> Old Lalazar, Rawalpindi - 051-5122055
    </div>

    <table class="info-table">
        <tr>
            <td><b>Due Date:</b> <?php echo e($monthlyFee->date); ?></td>
          <td></td>
          <td><b>Receipt No:</b> <?php echo e($monthlyFee->receipt_no); ?></td>
        </tr>
        <?php if($monthlyFee->student): ?>
        <tr>
            <td><b>Name:</b> <?php echo e($monthlyFee->student->full_name); ?></td>
            <td><b>Roll No. :</b> <?php echo e($monthlyFee->student->custom_id); ?></td>
            <td><b>Father's Name:</b> <?php echo e($monthlyFee->student->father_name); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <?php if($monthlyFee->student): ?> <td><b>Class:</b> <?php echo e($monthlyFee->student->grade_applied_for); ?></td>
            <?php endif; ?>


            <td><b>Fee Month:</b> <?php echo e($monthlyFee->fee_month); ?></td>
        </tr>
        <tr>
            <td><b>Receiving Date:</b> <?php echo e($monthlyFee->receiving_date); ?></td>
        </tr>
    </table>

    <table class="fee-table">
        <thead>
            <tr>
                <th>SR.NO</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>

            <?php $__currentLoopData = $monthlyFee->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($fee->description !== "Late Fee Fine /Day"): ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($fee->description); ?></td>
                    <td><?php echo e(number_format($fee->amount, 2)); ?></td>
                </tr>
            <?php elseif($fee->description === "Late Fee Fine /Day" && $fee->amount > 0): ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($fee->description); ?></td>
                    <td id="l_fine"></td>
                </tr>
                <input type="hidden" id="latefee" value="<?php echo e(number_format($fee->amount, 2)); ?>">
                <input type="hidden" id="duedate" value="<?php echo e($monthlyFee->date); ?>">
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="d-flex align-items-center gap-2">
            <b>Total:</b>
            <span id="total"><?php echo e(number_format($monthlyFee->total_amount, 2)); ?></span>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2">
            <b>Rs. (In Words):</b>
            <span id="t_words"><?php echo e($monthlyFee->amount_words); ?></span>
        </div>
    </div>


    <div class="signature">
        <?php if($monthlyFee->status=='paid'): ?>
        <img src="<?php echo e(asset('storage/receipts/imgpaid.png')); ?>" alt="Signature" style="width: 150px; height: auto; margin-top: 10px;"> <br>
              <?php endif; ?>
        <b>Receiver Name:</b> <?php echo e($monthlyFee->receiver_name); ?> <br>
        <b>Signature:</b> ______________________
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const lateFeePerDay = parseFloat($('#latefee').val()) || 0;
    const dueDateStr = $('#duedate').val();

    if (lateFeePerDay > 0 && dueDateStr) {
        const dueDate = new Date(dueDateStr);
        const currentDate = new Date();

        const timeDiff = currentDate - dueDate;
        const dayDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

        if (dayDiff > 0) {
            const totalLateFee = lateFeePerDay * dayDiff;
            const currentTotal = parseFloat(<?php echo json_encode($monthlyFee->total_amount, 15, 512) ?>) + totalLateFee;

            $('#l_fine').text('');
            $('#l_fine').text(totalLateFee.toFixed(2));
            $('#total').text('');
            $('#total').text( currentTotal.toFixed(2));

            // AJAX call to Laravel for number-to-words conversion
            $.ajax({
                url: '/convert-number-to-words',
                method: 'POST',
                data: {
                    amount: currentTotal,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#t_words').text('');
                    $('#t_words').text( response.words);
                },
                error: function () {
                    console.error("Error converting number to words");
                }
            });
        }
    }
});


</script>

<script>
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/fees/challan.blade.php ENDPATH**/ ?>