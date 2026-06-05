<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .print-container {
            width: 100%;
            padding: 20px;
            border: 1px solid #000;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .student-img {
            display: block;
            margin: 10px auto;
            width: 120px;
            height: auto;
            border-radius: 5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .details-table th, .details-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .footer {
            margin-top: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-container">
        <div class="header">
            <h2>Student Admission Record</h2>
            <p><strong>Student Name:</strong> <?php echo e($student->full_name); ?></p>
            <p><strong>Admission ID:</strong> <?php echo e($student->custom_id); ?></p>
        </div>

        <div class="text-center">
            <img src="<?php echo e(Storage::disk('public')->url( $student->passport_pic)); ?>" alt="Student Image" class="student-img">
        </div>

        <table class="details-table">
            <tr><th>Father's Name</th><td><?php echo e($student->father_name); ?></td></tr>
            <tr><th>Mother's Name</th><td><?php echo e($student->mother_name); ?></td></tr>
            <tr><th>Student CNIC</th><td><?php echo e($student->student_cnic); ?></td></tr>
            <tr><th>Father CNIC</th><td><?php echo e($student->father_cnic); ?></td></tr>
            <tr><th>Mother CNIC</th><td><?php echo e($student->mother_cnic); ?></td></tr>
            <tr><th>Guardian Name</th><td><?php echo e($student->guardian_name ?? 'N/A'); ?></td></tr>
            <tr><th>Religion</th><td><?php echo e($student->religion); ?></td></tr>
            <tr><th>Previous Class</th><td><?php echo e($student->pre_class); ?></td></tr>
            <tr><th>Grade Applied For</th><td><?php echo e($student->grade ? $student->grade->name : 'N/A'); ?></td></tr>
            <tr><th>Date of Birth</th><td><?php echo e($student->dob); ?></td></tr>
            <tr><th>Admission Date</th><td><?php echo e($student->admission_date); ?></td></tr>
            <tr><th>Residential Type</th><td><?php echo e($student->res_type); ?></td></tr>
            <tr><th>Contact Number</th><td><?php echo e($student->contact); ?></td></tr>
            <tr><th>Guardian Phone</th><td><?php echo e($student->guardian_phone); ?></td></tr>
            <tr><th>Guardian WhatsApp</th><td><?php echo e($student->guardian_whatsapp ?? 'N/A'); ?></td></tr>
            <tr><th>Domicile</th><td><?php echo e($student->domicile); ?></td></tr>
            <tr><th>Postal Address</th><td><?php echo e($student->postal_address); ?></td></tr>
            <tr><th>Father's Income</th><td><?php echo e(number_format($student->father_income, 2)); ?></td></tr>
              
                                        <!-- Display Applied Cadet Colleges -->
                                        <tr>
                                            <th>Applied Cadet Colleges</th>
                                            <td>
                                                <?php if($student->apply_cadet_colleges): ?>
                                                    <?php if($student->cadetColleges->isEmpty()): ?>
                                                        <span class="text-muted">No cadet colleges applied</span>
                                                    <?php else: ?>
                                                        <ul>
                                                            <?php $__currentLoopData = $student->cadetColleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li><?php echo e($college->college_name); ?></li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not applied for cadet colleges</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
        </table>

        <div class="footer">
            <p>Address: HighBrows Academy</p>
        </div>

        <div class="no-print">
            <button onclick="window.print()">Print</button>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/print-admission.blade.php ENDPATH**/ ?>