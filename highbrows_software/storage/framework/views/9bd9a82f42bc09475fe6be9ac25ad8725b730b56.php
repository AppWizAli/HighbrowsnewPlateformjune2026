<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div id="layoutSidenav">
    <?php if(auth()->user()->usertype == 'admin'): ?>
    <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(auth()->user()->usertype == 'subadmin'): ?>
    <?php echo $__env->make('subadmin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(auth()->user()->usertype == 'cordinator'): ?>
    <?php echo $__env->make('cordinator.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php else: ?>
<?php echo $__env->make('student.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

    <!-- Container for the form, adjusted to the right of the sidebar -->
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <div class="card shadow-lg">
                    <div class="card-header  text-white " style="background-color: #084298">
                        <h4>Monthly Fees</h4>
                    </div>
                    <div class="card-body">
                        <?php if($errors->any()): ?>
    <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>



<div class="container mt-4">
    <h2 class="text-center">Fee Receipt Form</h2>
    <form action="<?php echo e(route('monthlyfee.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>  

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="student_id" class="form-label">Name or Roll no.</label>
                <select name="student_id" id="student_id" class="form-select students" required>
                    <option value="" disabled selected>Select a Student</option>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($student->user_id); ?>"  data-admitted="true" <?php echo e(old('student_id') == $student->user_id ? 'selected' : ''); ?>>
                            <?php echo e($student->full_name); ?> - <?php echo e($student->custom_id); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"  data-admitted="false"  data-grade="<?php echo e($user->grade); ?>"  <?php echo e(old('student_id') == $user->id ? 'selected' : ''); ?>>
                            <?php echo e($user->name); ?> - <?php echo e('with no admission'); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Father's Name:</label>
                <input type="text" id="father_name" class="form-control" name="father_name" value="<?php echo e(old('father_name')); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Class:</label>

                <input type="text" id="class" class="form-control" name="class" value="<?php echo e(old('class')); ?>" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Due Date:</label>
                <input type="date" class="form-control" name="date" value="<?php echo e(old('date')); ?>" required>
                <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Receiving Date:</label>
                <input type="date" class="form-control mb-3" name="receiving_date" value="<?php echo e(old('receiving_date')); ?>" >
                <?php $__errorArgs = ['receiving_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fee For the Month of:</label>
                <input type="month" class="form-control" name="fee_month" value="<?php echo e(old('fee_month')); ?>" required>
                <?php $__errorArgs = ['fee_month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>SR.NO</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fees = [
                        'admission_fee' => 'Admission Fee (Non Refundable)',
                        'tuition_fee' => 'Tuition Fee',
                        'security_fee' => 'Security Fee',
                        'trip' => 'Trip',
                        'exam_fund' => 'Exam/Stationary/Sports fund/Com lab',
                        'school_functions' => 'School Functions',
                        'transport' => 'Transport',
                        'college_fee' => 'Colleges Fee',
                        'pocket_money' => 'Pocket Money',
                        'uniform_kit' => 'Uniform / Kit',
                        'fine' => 'Fine',
                        'late_fee_fine'=>'Late Fee Fine /Day',
                        'balance' => 'Balance',
                    ];
                    $sr = 1;
                    ?>

                    <?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($sr++); ?></td>
                        <td>
                            <input type="text" class="form-control" name="fees[<?php echo e($key); ?>][description]" value="<?php echo e(old('fees.' . $key . '.description', $value)); ?>" readonly required>
                            <?php $__errorArgs = ["fees.$key.description"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger mt-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </td>
                        <td>
                            <input type="number" class="form-control amount <?php if($key === 'tuition_fee'): ?> tuition-fee <?php endif; ?> <?php if($key === 'late_fee_fine'): ?> late_fee_fine <?php endif; ?>"
                                   name="fees[<?php echo e($key); ?>][amount]"
                                   value="<?php echo e(old('fees.' . $key . '.amount', 0)); ?>" required>
                            <?php $__errorArgs = ["fees.$key.amount"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger mt-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Discount (%):</label>
                <input type="number" id="discount" class="form-control" name="discount" value="<?php echo e(old('discount', 0)); ?>" min="0" max="100">
                <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Discounted Tuition Fee:</label>
                <input type="number" id="discountedTuitionFee" class="form-control" name="discounted_tuition_fee" value="<?php echo e(old('discounted_tuition_fee')); ?>" readonly>
                <?php $__errorArgs = ['discounted_tuition_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Total Amount:</label>
                <input type="number" id="totalAmount" class="form-control" name="total_amount" value="<?php echo e(old('total_amount')); ?>" readonly>
                <?php $__errorArgs = ['total_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Rs. (In Words):</label>
                <input type="text" class="form-control" name="amount_words" id="amount_words" readonly  required>
                <?php $__errorArgs = ['amount_words'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-2"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="row mb-3">
    
    <div class="col-md-6">
        <label class="form-label">Receiver Name:</label>
        <input type="text" class="form-control" name="receiver_name" value="<?php echo e(old('receiver_name')); ?>" required>
        <?php $__errorArgs = ['receiver_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-6">
        <label class="form-label">Generated By:</label>
        <input type="text" class="form-control" name="generated_by" id="generated_by" value="<?php echo e(auth()->user()->name); ?>" readonly>
    </div>
</div>


        <button type="submit" class="btn text-white" style="background-color: #084298">Submit</button>
    </form>

</div>




                    </div>
                </div>
            </div>
        </main>
    </div>

</div>


        </div>
        <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
        <script>
 CKEDITOR.replace('description');
        </script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!--<script>-->

<!--document.addEventListener('DOMContentLoaded', function () {-->

<!--    function calculateTotal() {-->
<!--        let total = 0;-->
<!--        let tuitionFee = 0;-->
<!--        let discount = Number(document.getElementById('discount').value) || 0;-->
<!--        let words='';-->
        // Loop through all .amount fields to sum them up
<!--        document.querySelectorAll('.amount').forEach(input => {-->
<!--            if (input.classList.contains('late_fee_fine')) {-->
<!--        return;-->
<!--    }-->
<!--            let amount = Number(input.value) || 0;-->
<!--            if (input.classList.contains('tuition-fee')) {-->
<!--                tuitionFee = amount;-->
<!--            } else {-->
<!--                total += amount;-->
<!--            }-->
<!--        });-->

        // Calculate the discounted tuition fee
<!--        let discountAmount = (tuitionFee * discount) / 100;-->
<!--        let discountedTuition = tuitionFee - discountAmount;-->

        // Set the discounted tuition and total amount in the fields
<!--        document.getElementById('discountedTuitionFee').value = discountedTuition.toFixed(2);-->
<!--        document.getElementById('totalAmount').value = (total + discountedTuition).toFixed(2);-->

        // Get the total amount value
<!--        const totalAmount = parseFloat(document.getElementById('totalAmount').value) || 0;-->

        // Send the total amount to the backend for conversion to words
<!--        fetch('/highbrows_software/convert-number-to-words', {-->
<!--            method: 'POST',-->
<!--            headers: {-->
<!--                'Content-Type': 'application/json',-->
<!--                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')-->
<!--            },-->
<!--            body: JSON.stringify({ amount: totalAmount })-->
<!--        })-->
<!--        .then(response => response.json())-->
<!--        .then(data => {-->
<!--            words=data.words-->
            // console.log(words);
            // Optionally show in HTML
<!--            let amountWordsElement = document.getElementById('amount_words');-->
<!--            if (amountWordsElement) {-->
                // Assign the words to the input element
<!--                amountWordsElement.value = words;-->
                // console.log(words); // For input fields
<!--            } else {-->
<!--                console.error('Element with id "amount_words" not found!');-->
<!--            }-->

<!--        })-->
<!--        .catch(error => console.error('Error converting to words:', error));-->

<!--    }-->

    // Add event listeners to dynamically detect changes
<!--    document.querySelectorAll('.amount, #discount').forEach(input => {-->
<!--        input.addEventListener('input', calculateTotal);-->
<!--    });-->

    // Run the function initially to set values
<!--    calculateTotal();-->
<!--});-->

<!--</script>-->
<script>

document.addEventListener('DOMContentLoaded', function () {

  function calculateTotal() {
    let total = 0;
    let discount = Number(document.getElementById('discount').value) || 0;

    // Sum all amounts except late_fee_fine
    document.querySelectorAll('.amount').forEach(input => {
        if (input.classList.contains('late_fee_fine')) return;
        let amount = Number(input.value) || 0;
        total += amount;
    });

    // Apply fixed discount to total
    let finalAmount = Math.max(total - discount, 0); // prevent negative total

    // Update total field
    document.getElementById('totalAmount').value = finalAmount.toFixed(2);

    // Convert to words via backend
    fetch('/highbrows_software/convert-number-to-words', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ amount: finalAmount })
    })
    .then(response => response.json())
    .then(data => {
        const amountWordsElement = document.getElementById('amount_words');
        if (amountWordsElement) {
            amountWordsElement.value = data.words;
        } else {
            console.error('Element with id "amount_words" not found!');
        }
    })
    .catch(error => console.error('Error converting to words:', error));
}

// Attach event listeners
document.querySelectorAll('.amount, #discount').forEach(input => {
    input.addEventListener('input', calculateTotal);
});

// Run initially
calculateTotal();

});

</script>

<script>
//     $(document).ready(function() {
//     $('.students').select2();
// });
$(document).ready(function () {
    $('.students').select2();

    $('#student_id').change(function () {
        let studentId = $(this).val();
        let selectedOption = $(this).find('option:selected');
        let isAdmitted = selectedOption.data('admitted');

        if (studentId && isAdmitted) {
            $.ajax({
                url: '/highbrows_software/get-student-details/' + studentId,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    
                    if (response) {
                        $('#father_name').val(response.father_name);
                        $('#class').val(response.class);
                    } else {
                        $('#father_name').val('');
                        $('#class').val('');
                    }
                },
                error: function () {
                    $('#father_name').val('');
                    $('#class').val('');
                }
            });
        } else {
            $('#father_name').val('');
            $('#class').val(selectedOption.data('grade') || '');
        }
    });
});


</script>


</div>



        <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/fees/add.blade.php ENDPATH**/ ?>