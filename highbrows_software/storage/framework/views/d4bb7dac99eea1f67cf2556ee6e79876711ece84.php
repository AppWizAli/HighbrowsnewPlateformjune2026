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
                    <form action="<?php echo e(route('salary.createForCurrentMonth')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn my-4 text-white p-2" style="background-color: #084298">
                            Add Salaries
                        </button>
                    </form>
                </div>
                
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Employee Salries</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Salaries List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Teacher Name</th>
                                                <th>Salary Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $salaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($salary->teacher->name); ?></td>
                                                    <td><?php echo e($salary->teacher->salary); ?></td>
                                                    <td><?php echo e($salary->status); ?></td>
                                                    <td>
                                                        <?php if($salary->status == 'unpaid'): ?>
                                                            <!-- Pay Button -->
                                                            <a href="<?php echo e(route('salary.pay', $salary->id)); ?>" 
                                                                class="btn btn-success btn-sm" 
                                                                onclick="return confirm('Are you sure you want to mark this salary as paid?');">Pay</a>
                                                        <?php endif; ?>
                                    
                                                        <!-- Print Receipt Button -->
                                                        <a href="<?php echo e(route('salary.receipt', $salary->id)); ?>" 
                                                            class="btn btn-primary btn-sm" 
                                                            target="_blank">Print Receipt</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <?php echo e($salaries->links()); ?> 
                                    
                                
                                    
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/employees-salaries.blade.php ENDPATH**/ ?>