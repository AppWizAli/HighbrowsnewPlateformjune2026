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
            <div class="container">
                <div class="d-flex justify-content-start">
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="<?php echo e(route('fees.create')); ?>">Generate Fee Challan</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Generate Fee Challan</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Fee Records</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Total Fee</th>
                                                <th>Advance</th>
                                                <th>Status</th>
                                                <th>Installments</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($loop->iteration); ?></td>
                                                    <td><?php echo e($fee->student->full_name ?? 'N/A'); ?></td>
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
                                                                <p>
                                                                    Amount: <?php echo e($install->amount); ?> | Due Date: <?php echo e($install->due_date); ?> |
                                                                    <?php if($install->receipt): ?>
                                                                        <!-- Display the receipt link if available -->
                                                                        <a href="<?php echo e(asset('storage/receipts/' . $install->receipt)); ?>" class="btn btn-info" target="_blank" title="view">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <span class="text-warning">No receipt uploaded</span>
                                                                    <?php endif; ?>
                                                                    <a href="<?php echo e(route('challan.generate', $install->id)); ?>" class="btn btn-primary" title="Generate Challan">
                                                                        <i class="fa-solid fa-print"></i>
                                                                    </a>
                                                                    <a href="#" class="btn btn-primary"  title="Update Status"  id="status-btn-<?php echo e($install->id); ?>"
                                                                    onclick="submitStatusAjax(<?php echo e($install->id); ?>); return false;" >
                                                                     <i class="fa-solid fa-close" id="status-icon-<?php echo e($install->id); ?>"></i>
                                                                 </a>

                                                                </p>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                            <p>No installments found.</p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        
                                                        <a href="<?php echo e(route('receipt.generate', $fee->id)); ?>" class="btn btn-success" title="Generate Receipt">
                                                            <i class="fa-solid fa-receipt"></i>
                                                        </a>
                                                        <form action="<?php echo e(route('fees.destroy', $fee->id)); ?>" method="POST" style="display:inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this fee record?')" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>


<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
function submitStatusAjax(id) {
    const icon = document.getElementById('status-icon-' + id);
    const btn = document.getElementById('status-btn-' + id);

    if (!icon || !btn) {
        console.error('DOM elements not found');
        return;
    }

    // Show loading spinner
    icon.className = 'fa-solid fa-spinner fa-spin';
    icon.style.color = '';

    fetch(`/status/update/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to fetch');
        return response.json();
    })
    .then(data => {
        try {
            console.log('✅ Response:', data);

            if (data.status === 'Paid') {
                icon.className = 'fa-solid fa-check';
                icon.style.color = 'limegreen';
                btn.disabled = true;
            } else {
                icon.className = 'fa-solid fa-xmark';
                icon.style.color = 'orange';
            }

        } catch (err) {
            console.error('❌ Error inside .then():', err);
            icon.className = 'fa-solid fa-xmark';
            icon.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('🚨 Caught in .catch():', error);
        icon.className = 'fa-solid fa-xmark';
        icon.style.color = 'red';
    });
}
    </script>

<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/student-fees.blade.php ENDPATH**/ ?>