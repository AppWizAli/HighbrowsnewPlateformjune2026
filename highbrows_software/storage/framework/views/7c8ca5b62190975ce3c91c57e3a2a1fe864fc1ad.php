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
                <?php if(auth()->user()->usertype == 'admin' || auth()->user()->usertype == 'subadmin'): ?>
                <div class="d-flex justify-content-start">
                    
                    <a class="btn my-4 text-white p-2 " style="background-color: #084298" href="<?php echo e(route('exams.create')); ?>">Add New Exam</a>
                </div>
                <?php endif; ?>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-book"></i> Exams</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Exams List</h4>
                                <form class="mt-1" action="<?php echo e(route('result-add')); ?>" method="GET">
                                    <?php echo csrf_field(); ?>
                                
                                    <div class="row mt-3">
                                        <!-- Exam Name -->
                                        <div class="col-md-3 px-5">
                                            <label for="exam">Exam Name</label>
                                            <select class="form-control" name="exam" required>
                                                <option value="" disabled selected>Select Exam</option>
                                                <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?></option>
                                
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                
                                        <!-- Class Name -->
                                        <div class="col-md-3 mb-3">
                                            <label  for="class">Class Name</label>
                                            <select class="form-control" name="class" required>
                                                <option value="" disabled selected>Select Class</option>
                                                <?php $__currentLoopData = $classe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                            </select>
                                        </div>
                                
                                   
                                        <!-- Subject -->
                                        <div class="col-md-3 mb-3">
                                            <label  for="subject">Subject</label>
                                            <select class="form-control" name="subject" required>
                                                <option value="" disabled selected>Select Subject</option>
                                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->subj_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                <div class="text-center">
                                    <button type="submit" class="btn mt-3" style="background-color: #084298;color:white;"> Add Marks </button>
                                </div>
                                
                                
                                </form>
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
    function confirmDelete(employeeId) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + employeeId).submit();
        }
      })
    }

 
  </script><?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/result.blade.php ENDPATH**/ ?>