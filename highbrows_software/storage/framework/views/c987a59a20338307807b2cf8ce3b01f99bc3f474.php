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
                    <h4 class="card-title my-4">Edit Exam</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Exam</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form class="mt-4" action="<?php echo e(route('exams.update', $exam->id)); ?>" method="POST">
                          <?php echo csrf_field(); ?>
                          <?php echo method_field('PUT'); ?>
                          <div class="mb-3">
                            <label for="examName" class="form-label">Exam Name <span class="text-danger">*</span></label>
                            <input
                              type="text"
                              class="form-control"
                              id="examName"
                              name="name"
                              placeholder="Enter exam name"
                              value="<?php echo e(old('name', $exam->name)); ?>"
                              required
                            />
                          </div>
                      
                          <div class="mb-3">
                            <label for="examNote" class="form-label">Note</label>
                            <textarea
                              class="form-control"
                              id="examNote"
                              rows="3"
                              name="note"
                              placeholder="Enter any notes"
                            ><?php echo e(old('note', $exam->note)); ?></textarea>
                          </div>
                      
                          <button type="submit" class="btn" style="background-color: #084298; color: white;">
                            Save Changes
                          </button>
                        </form>
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/edit-exam.blade.php ENDPATH**/ ?>