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
    <form action="<?php echo e(route('monthlyfee.update', $monthlyFee->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?> 

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="student_id" class="form-label">Name or Roll no.</label>
                <select name="student_id" id="student_id" class="form-select students" required>
                    <option value="" disabled selected>Select a Student</option>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($student->user_id); ?>"  data-admitted="true" <?php echo e(old('student_id',$monthlyFee->student_id) == $student->user_id ? 'selected' : ''); ?>>
                            <?php echo e($student->full_name); ?> - <?php echo e($student->custom_id); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>"  data-admitted="false"  data-grade="<?php echo e($user->grade); ?>"  <?php echo e(old('student_id',$monthlyFee->user_id) == $user->id ? 'selected' : ''); ?>>
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
                <label class="form-label">Date:</label>
                <input type="date" class="form-control" name="date" value="<?php echo e(old('date', $monthlyFee->date)); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Receiving Date:</label>
                <input type="date" class="form-control" name="receiving_date" value="<?php echo e(old('receiving_date', $monthlyFee->receiving_date)); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Fee For the Month of:</label>
                <input type="month" class="form-control" name="fee_month" value="<?php echo e(old('fee_month', $monthlyFee->fee_month)); ?>" required>
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
                    <?php $sr = 1; ?>
                    <?php $__currentLoopData = $monthlyFee->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($sr++); ?></td>
                        <td>
                            <input type="text" class="form-control" name="fees[<?php echo e($loop->index); ?>][description]" 
                                   value="<?php echo e(old("fees.$loop->index.description", $fee->description)); ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control amount" 
                                   name="fees[<?php echo e($loop->index); ?>][amount]" 
                                   value="<?php echo e(old("fees.$loop->index.amount", $fee->amount)); ?>" required>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

     <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Discount (%):</label>
                <input type="number" id="discount" class="form-control" name="discount" value="<?php echo e(old('discount', $monthlyFee->discount)); ?>" min="0" max="100">
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
            <!--<div class="col-md-6">-->
            <!--    <label class="form-label">Discounted Tuition Fee:</label>-->
            <!--    <input type="number" id="discountedTuitionFee" class="form-control" name="discounted_tuition_fee" value="<?php echo e(old('discounted_tuition_fee', $monthlyFee->discounted_tuition_fee)); ?>" readonly>-->
            <!--    <?php $__errorArgs = ['discounted_tuition_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>-->
            <!--        <div class="text-danger mt-2"><?php echo e($message); ?></div>-->
            <!--    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>-->
            <!--</div>-->
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Total Amount:</label>
                <input type="number" id="totalAmount" class="form-control" name="total_amount" value="<?php echo e(old('total_amount', $monthlyFee->total_amount)); ?>" readonly>
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
                <input type="text" class="form-control" name="amount_words" id="amount_words" value="<?php echo e(old('amount_words', $monthlyFee->amount_words)); ?>" readonly  required>
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
        <input type="text" class="form-control" name="receiver_name" value="<?php echo e(old('receiver_name', $monthlyFee->receiver_name)); ?>" required>
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
        <input type="text" class="form-control" name="generated_by" id="generated_by" value="<?php echo e(old('receiver_name', $monthlyFee->generated_by)); ?>" readonly>
    </div>
        </div>


      

 

        <button type="submit" class="btn btn-primary">Update</button>
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/fees/edit.blade.php ENDPATH**/ ?>