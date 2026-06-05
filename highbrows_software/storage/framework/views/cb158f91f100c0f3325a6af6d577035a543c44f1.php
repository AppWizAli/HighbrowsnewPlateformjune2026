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
                    <h4 class="card-title my-4">Edit Rules</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Terms and Conditions</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form action="<?php echo e(route('studentcondition.update', $rule->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                
                         
                
                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Rules</label>
                                <textarea class="form-control" id="description" name="rules"><?php echo e($rule->rules); ?></textarea>
                            </div>
                
                            <!-- Submit Button -->
                            <button type="submit" class="btn text-white" style="background-color: #084298">Update</button>
                            <a href="<?php echo e(route('studentcondition.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('description');
</script>
<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/terms/edit-student.blade.php ENDPATH**/ ?>