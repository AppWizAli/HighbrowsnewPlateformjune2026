


<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div id="layoutSidenav">
    <?php echo $__env->make(auth()->user()->usertype === 'admin' ? 'admin.sidebar' : (auth()->user()->usertype === 'subadmin' ? 'subadmin.sidebar' : (auth()->user()->usertype === 'cordinator' ? 'cordinator.sidebar' : 'student.sidebar')), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
    .blue {
    background-color: #007bff;
    color: white;
}
</style>
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <h4>Expenses List</h4>

                <form method="GET" action="<?php echo e(route('expenses.index')); ?>" class="row mb-3">
                    <div class="col-md-3">
                        <label for="month" class="form-label">Month</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">Select Month</option>
                            <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                                    <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="year" class="form-label">Year</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">Select Year</option>
                            <?php $__currentLoopData = range(now()->year, 2020); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="<?php echo e(route('expenses.index')); ?>" class="btn btn-secondary">Reset</a>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                        <a href="<?php echo e(route('expenses.create')); ?>" class="btn btn-success">Add Expense</a>
                    </div>
                </form>

                <div class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead class="blue">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Total (Rs)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>


                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($expense->date)->format('d-M-Y')); ?></td>

                            <!-- Combine all descriptions into one column -->
                            <td>
                                <?php $__currentLoopData = $expense->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($desc['description']); ?><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>

                            <!-- Calculate the total sum of amounts -->
                            <td><?php echo e(number_format(collect($expense->details)->sum('amount'), 2)); ?></td>

                            <!-- Actions (Edit and Delete) -->
                            <td class="align-middle text-center">
    
    <?php if($expense->image): ?>
    
    <a href="<?php echo e(Storage::disk('public')->url($expense->image)); ?>" target="_blank" class="d-block mb-2" title="View Receipt">
        <i class="fas fa-eye text-success"></i>
    </a>
<?php else: ?>
    
    <form action="<?php echo e(route('expense.upload', $expense->id)); ?>" method="POST" enctype="multipart/form-data" style="display: inline;">
        <?php echo csrf_field(); ?>
        <label for="upload-<?php echo e($expense->id); ?>" style="cursor: pointer;">
            <i class="fas fa-upload text-primary mb-2" title="Upload Receipt"></i>
        </label>
        <input id="upload-<?php echo e($expense->id); ?>" type="file" name="image" style="display: none;" onchange="this.form.submit()">
    </form>
<?php endif; ?>

    
    <a href="<?php echo e(route('expenses.edit', $expense->id)); ?>" class="btn btn-sm btn-warning">Edit</a>

    
    <form action="<?php echo e(route('expenses.destroy', $expense->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button class="btn btn-sm btn-danger">Delete</button>
    </form>
</td>

                        </tr>
                      

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center">No records found</td></tr>
                    <?php endif; ?>

                    </tbody>
                </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/expenses/index.blade.php ENDPATH**/ ?>