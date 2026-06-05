<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                <div class="d-flex justify-content-start">
                    <h4 class="card-title my-4">Add New College</h4>
                    
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Fees</h4>

                    </div>
                    <div class="card-body bg-light">


                        <form action="<?php echo e(route('fees.store')); ?>" method="POST" class="p-4 shadow rounded bg-white">
                            <?php echo csrf_field(); ?>
                            <h4 class="mb-4 text-center" style="color: #084298;">Add Fee Details</h4>

                            <!-- Student Selection -->
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student</label>
                                <select name="student_id" id="student_id" class="form-select" required>
                                    <option value="" disabled selected>Select a Student</option>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($student->id); ?>" <?php echo e(old('student_id') == $student->id ? 'selected' : ''); ?>><?php echo e($student->name); ?></option>
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

                            <!-- Total Fee and Advance -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="total_fee" class="form-label">Total Fee</label>
                                    <input type="number" name="total_fee" class="form-control" placeholder="Enter total fee" value="<?php echo e(old('total_fee')); ?>" required>
                                    <?php $__errorArgs = ['total_fee'];
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
                                <div class="col-md-6 mb-3">
                                    <label for="advance" class="form-label">Advance</label>
                                    <input type="number" name="advance" class="form-control" placeholder="Enter advance amount" value="<?php echo e(old('advance')); ?>">
                                    <?php $__errorArgs = ['advance'];
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

                            <!-- Number of Installments -->
                            <div class="mb-3">
                                <label for="installments" class="form-label">No. of Installments</label>
                                <input type="text" name="installments" class="form-control" id="installments" placeholder="Enter installments number" oninput="createInstallmentFields()" value="<?php echo e(old('installments')); ?>" required>
                                <?php $__errorArgs = ['installments'];
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

                            <!-- Dynamic Installment Inputs -->
                            <div id="installment-container"></div>



                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100" style="background-color: #084298; color: white;">Submit</button>
                        </form>



                    </div>
                </div>
            </div>

        </main>
    </div>

</div>


        </div>
<script>function createInstallmentFields() {
    const numInstallments = document.getElementById('installments').value;

    const container = document.getElementById('installment-container');
    container.innerHTML = ''; // Clear existing fields

    if (numInstallments && numInstallments > 0) {
        for (let i = 1; i <= numInstallments; i++) {
            const card = document.createElement('div');
            card.classList.add('card', 'mb-3', 'p-3');

            const cardHeader = document.createElement('h5');
            cardHeader.classList.add('card-title');
            cardHeader.innerText = 'Installment ' + i;
            card.appendChild(cardHeader);

            const row = document.createElement('div');
            row.classList.add('row');

            const amountCol = document.createElement('div');
            amountCol.classList.add('col-md-6', 'mb-3');
            const labelAmount = document.createElement('label');
            labelAmount.setAttribute('for', 'installment_amount_' + i);
            labelAmount.classList.add('form-label');
            labelAmount.innerText = 'Amount';
            amountCol.appendChild(labelAmount);
            const inputAmount = document.createElement('input');
            inputAmount.setAttribute('type', 'number');
            inputAmount.setAttribute('name', 'installments[' + i + '][amount]');
            inputAmount.setAttribute('id', 'installment_amount_' + i);
            inputAmount.classList.add('form-control');
            inputAmount.placeholder = 'Enter amount for installment ' + i;
            amountCol.appendChild(inputAmount);
            row.appendChild(amountCol);

            const dateCol = document.createElement('div');
            dateCol.classList.add('col-md-6', 'mb-3');
            const labelDate = document.createElement('label');
            labelDate.setAttribute('for', 'installment_date_' + i);
            labelDate.classList.add('form-label');
            labelDate.innerText = 'Payment Date';
            dateCol.appendChild(labelDate);
            const inputDate = document.createElement('input');
            inputDate.setAttribute('type', 'date');
            inputDate.setAttribute('name', 'installments[' + i + '][due_date]');
            inputDate.setAttribute('id', 'installment_date_' + i);
            inputDate.classList.add('form-control');
            dateCol.appendChild(inputDate);
            row.appendChild(dateCol);

            card.appendChild(row);
            container.appendChild(card);
        }
    }
}
</script>
        <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/add-fees.blade.php ENDPATH**/ ?>