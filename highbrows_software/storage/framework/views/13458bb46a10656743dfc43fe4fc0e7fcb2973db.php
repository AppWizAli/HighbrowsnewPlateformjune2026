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
                    <h4 class="card-title my-4">Select Class For Marking Attandance</h4>
                    
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-clock"></i> Students Attandance</h4>
                     
                    </div>
                    <div class="card-body bg-light">
           
                        <form id="searchForm" method="GET" action="<?php echo e(route('add_student_attendance')); ?>" class="mb-4 mx-3">
                         
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class">Class</label>
                                        <select name="class" id="class" class="form-control">
                                            <option value="">Select Class</option>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($class->id); ?>" <?php echo e($class->id == request()->query('class') ? 'selected' : ''); ?>>
                                                    <?php echo e($class->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="mt-3">
                                <button type="submit" class="btn px-4 text-white" style="background-color: #084298">Add Attendance</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>


        </div>
        <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/select-class.blade.php ENDPATH**/ ?>