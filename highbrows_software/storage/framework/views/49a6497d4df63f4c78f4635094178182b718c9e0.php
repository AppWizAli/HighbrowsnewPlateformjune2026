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
              <div class="d-flex justify-content-start mb-4">

                  <a href="<?php echo e(route('employee_add_attendance')); ?>" class="btn " style="background-color: #084298;color:white;">Add new attendance</a>

            </div>
                <div class="card shadow-lg">
                    <div class="card-header  text-white " style="background-color: #084298">
                      <h4 class="card-title">Employees Attendance</h4>
                    </div>

                    <div class="table-responsive">
                      <div id="attendanceTable" class="mt-4">
                        <table class="table table-bordered text-center">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Employee</th>

                              <th>Email</th>
                              <th>Attendance</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $count = $employees->firstItem();
                            ?>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($count++); ?></td>
                                <td><?php echo e($employee->name); ?></td>

                                <td><?php echo e($employee->email); ?></td>
                                <td>
                                  <a href="<?php echo e(route('show_employee_attendace', ['id' => $employee->id])); ?>" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                  </a>
                                </td>
                              </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                        </table>
                      </div>
                      <!-- Pagination -->
                      <div class="mt-3">
                        <?php echo e($employees->links('pagination::bootstrap-5')); ?>

                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </main>
    </div>

</div>


        </div>
        <script>
    document.getElementById('apply_cadet_colleges').addEventListener('change', function () {
    const cadetCollegesList = document.getElementById('cadetCollegesList');
    cadetCollegesList.style.display = this.checked ? 'block' : 'none';
});

        </script>

        <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/attendance/viewEmployee.blade.php ENDPATH**/ ?>