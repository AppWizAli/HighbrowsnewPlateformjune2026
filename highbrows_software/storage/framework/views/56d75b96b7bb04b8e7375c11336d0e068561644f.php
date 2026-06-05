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
                    
                    <a class="btn my-4 text-white p-2 " style="background-color: #084298" href="<?php echo e(route('exams.create')); ?>">Add New Exam</a>
                </div>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatablesSimple">
                                        <thead class="">
                                          <tr>
                                            <th>ID</th>
                                            <th>Exam Name</th>
                                            <th>Note</th>
                                            <th>Actions</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                              <td><?php echo e($exam->id); ?></td>
                                              <td><?php echo e($exam->name); ?></td>
                                              <td><?php echo e($exam->note); ?></td>
                                              <td>
                                                <!-- Edit Button -->
                                                <a href="<?php echo e(route('exams.edit',  $exam->id)); ?>" class="btn btn-warning btn-sm mx-1" title="Edit">
                                                  <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('exams.destroy',  $exam->id)); ?>" method="POST" style="display:inline;">
                                                  <?php echo csrf_field(); ?>
                                                  <?php echo method_field('DELETE'); ?>
                                                  <button type="submit" class="btn btn-danger btn-sm mx-1" title="Delete">
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

 
  </script><?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/exams.blade.php ENDPATH**/ ?>