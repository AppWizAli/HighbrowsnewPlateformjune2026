<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<style>
    #layoutSidenav {
        display: flex;
        width: 100%;
    }

    #layoutSidenav .sidebar {
        width: 250px;
    }

    main {
        flex: 1;
        padding: 20px;
        background-color: #f8f9fa;
    }
</style>

<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div id="layoutSidenav" class="d-flex">
    <!-- Sidebar Section -->
    <div class="sidebar">
        <?php if(auth()->user()->usertype == 'admin'): ?>
        <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <!-- Include the Admin Sidebar -->
    <?php elseif(auth()->user()->usertype == 'subadmin'): ?>
        <?php echo $__env->make('subadmin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <!-- Include the Subadmin Sidebar -->
    <?php else: ?>
    <?php echo $__env->make('student.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    </div>

    <!-- Main Content Section -->
    <main class="flex-grow-1">
        <div class="container-fluid px-4">
            <h3 class="mt-5">Welcome, <?php echo e(Auth::user()->name); ?></h3>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>

            <!-- Fee Record Section -->
            <?php if($fees->isEmpty()): ?>
                <div class="alert alert-warning text-center">
                    No fee record available.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Total Fee</th>
                                <th>Advance</th>
                                <th>Status</th>
                                <th>Installments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($fee->total_fee); ?></td>
                                    <td><?php echo e($fee->advance); ?></td>
                                    <td>
                                        <span class="badge
                                            <?php if($fee->status == 'Paid'): ?> bg-success
                                            <?php elseif($fee->status == 'Pending'): ?> bg-warning
                                            <?php elseif($fee->status == 'Overdue'): ?> bg-danger
                                            <?php else: ?> bg-secondary
                                            <?php endif; ?>">
                                            <?php echo e($fee->status); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($fee->installment->isNotEmpty()): ?>
                                            <?php $__currentLoopData = $fee->installment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $install): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="mb-2 p-2 border rounded">
                                                    <p>Amount: <?php echo e($install->amount); ?> | Due: <?php echo e($install->due_date); ?></p>

                                                    <!-- Upload Receipt Form -->
                                                    <form action="<?php echo e(route('receipt.upload', $install->id)); ?>" method="POST" enctype="multipart/form-data">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="input-group">
                                                            <input type="file" name="receipt" class="form-control" required>
                                                            <button type="submit" class="btn btn-primary">Upload</button>
                                                        </div>
                                                    </form>

                                                    <!-- Show Uploaded Receipt -->
                                                    <?php if($install->receipt): ?>
                                                        <a href="<?php echo e(asset('storage/receipts/' . $install->receipt)); ?>" target="_blank" class="btn btn-success btn-sm mt-2">
                                                            View Receipt
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <p>No Installments</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/student/fee-record.blade.php ENDPATH**/ ?>