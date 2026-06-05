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
            
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Student Attendance</h4>
                     
                    </div>
                    <div class="card-body bg-light">
                    <form action="<?php echo e(route('student_attendance_store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group ">
                            <label for="date">Date:</label>
                            <input type="date" name="date" id="date" class="form-control w-50" required>
                            <button type="button" id="toggleAttendance" class="btn  mt-3" style="color: white;background-color:#084298">Add Attendance</button>
                        
                        </div>
                        
                        <!-- Attendance Table -->
                        <div id="attendanceTable" class="mt-4" style="display: none;">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Roll_No</th>
                                        <th>Father Contact</th>
                                        <th>Attendance</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $count=0;
                                  ?>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(++$count); ?></td>
                                        <td><?php echo e($student->full_name); ?></td>
                                        <td><?php echo e($student->custom_id); ?></td>
                                        <td><?php echo e($student->guardian_phone); ?>

                                          <input type="text" name="class" value=<?php echo e($student->grade_applied_for); ?> hidden>
                                        </td>
                                        
                                        
                                        <td>
                                             <input type="radio" name="attendance[<?php echo e($student->id); ?>]" value="present" > Present 
                                        
                                           <input type="radio" name="attendance[<?php echo e($student->id); ?>]" value="absent">  Absent
                                       
                                            <input type="radio" name="attendance[<?php echo e($student->id); ?>]" value="leave">  Leave
                                       

                                        </td>
                                        
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success mt-3">Submit Attendance</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>


        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.getElementById('date');
            var today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
        });
        document.getElementById('toggleAttendance').addEventListener('click', function() {
            var attendanceTable = document.getElementById('attendanceTable');
            if (attendanceTable.style.display === "none") {
                attendanceTable.style.display = "block";
            } else {
                attendanceTable.style.display = "none";
            }
        });
      </script>
        <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/student-attendance.blade.php ENDPATH**/ ?>