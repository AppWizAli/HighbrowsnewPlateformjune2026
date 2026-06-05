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
                    <h4 class="card-title my-4">Update DateSheet</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> DateSheet</h4>
                    </div>
                    <div class="card-body bg-light">
           
                        <form class="mt-1" action="<?php echo e(route('exam.schedule.datesheet.update', ['id' => $exam->id])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="exam_schedule_id" value="<?php echo e($exam->exam_schedule_id); ?>">
                            <div class="row">
                                <div class="col-md-3 px-5">
                                    <label class="form-control" for="subject"><?php echo e($exam->subject->subj_name); ?></label>
                                </div>
        
                                <!-- Date Selection -->
                                <div class="col-md-3 mb-3">
                                    <input type="date" class="form-control" name="date" value="<?php echo e($exam->date ?? ''); ?>" required>
                                </div>
        
                                <!-- Start Time -->
                                <div class="col-md-3 mb-3">
                                    <input type="time" class="form-control" name="start_time" value="<?php echo e($exam->start_time ?? ''); ?>" required>
                                </div>
        
                                <!-- End Time -->
                                <div class="col-md-3 mb-3">
                                    <input type="time" class="form-control" name="end_time" value="<?php echo e($exam->end_time ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn " style="background-color: #084298;color:white;">Update Exam Schedule</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/datesheet_edit.blade.php ENDPATH**/ ?>