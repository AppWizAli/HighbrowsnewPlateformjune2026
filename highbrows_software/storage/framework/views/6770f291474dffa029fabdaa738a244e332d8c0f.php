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
                    <h4 class="card-title my-4">Edit Profile</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> Teacher</h4>
                    </div>
                    <div class="card-body bg-light">
           
                        <form method="POST" action="<?php echo e(route('subadmin.update', $subadmin->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo e($subadmin->name); ?>" required>
                            </div>
                
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact:</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo e($subadmin->contact); ?>" required>
                            </div>
                
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo e($subadmin->email); ?>" required>
                            </div>
                
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password (Leave blank if not changing):</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                
                            <button type="submit" class="btn " style="background-color: #084298;color:white;">Update</button>
                            <a href="<?php echo e(route('subadmin.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/subadmin/edit-subadmin.blade.php ENDPATH**/ ?>