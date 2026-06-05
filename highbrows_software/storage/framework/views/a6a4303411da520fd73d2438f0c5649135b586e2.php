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
                <div class="card shadow-lg">
                    <div class="card-header  text-white " style="background-color: #084298">
                        <h2>DateSheet</h2>
                    </div>
                    <div class="card-body">
                        

<form class="mt-1" action="<?php echo e(route('datesheet.store', ['id' => $exam->id])); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
        <div class="col-md-3 px-5">
            <label class="form-control" for="subject"><?php echo e($subject->subj_name); ?></label>
            <input type="hidden" class="form-control" name="subjects[<?php echo e($loop->index); ?>][subject_id]" value="<?php echo e($subject->id); ?>" required>
        </div>

        <!-- Date Selection -->
        <div class="col-md-3 mb-3">
            <input type="date" class="form-control" name="subjects[<?php echo e($loop->index); ?>][date]" required>
        </div>

        <!-- Start Time -->
        <div class="col-md-3 mb-3">
            <input type="time" class="form-control" name="subjects[<?php echo e($loop->index); ?>][start_time]" required>
        </div>

        <!-- End Time -->
        <div class="col-md-3 mb-3">
            <input type="time" class="form-control" name="subjects[<?php echo e($loop->index); ?>][end_time]" required>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="text-center">
        <button type="submit" class="btn " style="background-color: #084298;color:white;">Add Exam Schedule</button>
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/date_sheet.blade.php ENDPATH**/ ?>