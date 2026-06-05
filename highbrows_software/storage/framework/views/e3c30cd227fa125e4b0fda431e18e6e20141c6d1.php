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
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="<?php echo e(route('subadmin.create')); ?>">Add New Teacher</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Teachers</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Teachers List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="examsTable">
                                        <thead>
                                          <tr>
                                            <th>#</th>
                                            <th>Jioning</th>

                                            <th>Profile Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            

                                            <th><i class="fa fa-ellipsis-h"></i></th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <!-- Example Row, you can dynamically generate rows using Blade templates -->
                                          <?php
                                              $count=0;
                                          ?>
                                          <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <tr>
                                            <td><?php echo e(++$count); ?></td>
                                            <td><?php echo e($employee->joining_date); ?></td>

                                            <td><img src=" <?php echo e(Storage::disk('public')->url($employee->image)); ?>" alt="Employee Image" style="width: 25px; height: auto;margin-right:4px"></td>
                                            <td><?php echo e($employee->name); ?></td>
                                            <td><?php echo e($employee->email); ?></td>

                                            

                                            <td>
                                              <!-- View Button -->
                                              <a href="<?php echo e(route('teachers.show', $employee->id )); ?>" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                              </a>

                                              <!-- Edit Button -->
                                              <a href="<?php echo e(route('teachers.edit',  $employee->id)); ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                              </a>

                                              <!-- Delete Button -->
                                              <form id="delete-form-<?php echo e($employee->id); ?>"
                                                action="<?php echo e(route('teachers.destroy', $employee->id)); ?>"
                                                method="POST"
                                                style="display:inline;">
                                              <?php echo csrf_field(); ?>
                                              <?php echo method_field('DELETE'); ?>
                                              <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                                      onclick="confirmDelete(<?php echo e($employee->id); ?>)">
                                                  <i class="fas fa-trash"></i>
                                              </button>
                                          </form>


                                            </td>
                                          </tr>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                          <!-- Add more rows here -->
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
<script>
    function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this teacher?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>


<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/viewall-employees.blade.php ENDPATH**/ ?>