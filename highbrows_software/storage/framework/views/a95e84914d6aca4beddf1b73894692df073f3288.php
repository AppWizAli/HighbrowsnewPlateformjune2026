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
                    <a href="<?php echo e(route('attendance')); ?>" class="btn " style="background-color: #084298;color:white;">Add new attendance</a>
                    
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Attendance</h4>

                    </div>
                    <div class="card-body bg-light">

                                  <!-- Search Form -->


              <!-- Time Table -->
              <!-- Table for Class, Section, and Students -->
              <div class="card mt-4">
                <div class="card-body">
                  <h4 class="card-title">Class and Student Information</h4>
                  <div class="container mt-4">
                    <h4>View Attendance</h4>
                    <form id="filterForm" class="mb-4">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="class_id">Class</label>
                                <select name="class_id" id="class_id" class="form-control">
                                    <option value="">Select Class</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="date" class="form-control">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" id="filterButton" class="btn" style="background-color: #084298;color:white">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="attendanceTable" class="table-responsive" style="display: none;">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Roll No</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceBody"></tbody>
                    </table>
                </div>
                <div id="attendanceSummary" class="mt-4" style="display: none;">
                    <h5>Attendance Summary</h5>
                    <ul class="list-group">
                        <li class="list-group-item">Total Students: <span id="totalStudents">0</span></li>
                        <li class="list-group-item">Present: <span id="presentStudents">0</span></li>
                        <li class="list-group-item">Absent: <span id="absentStudents">0</span></li>
                        <li class="list-group-item">On Leave: <span id="onLeaveStudents">0</span></li>
                    </ul>
                </div>

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
   document.getElementById('filterButton').addEventListener('click', function () {
    const classId = document.getElementById('class_id').value;
    const date = document.getElementById('date').value;

    if (!classId || !date) {
        alert('Please select both class and date.');
        return;
    }

    fetch(`/highbrows_software/attendance/filter?class_id=${classId}&date=${date}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('attendanceBody');
            tbody.innerHTML = '';
              console.log(data);
            if (!data.students || data.students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4">No students found for this class and date.</td></tr>';
                document.getElementById('attendanceSummary').style.display = 'none';
                return;
            }

           data.students.forEach((student, index) => {
    const attendanceRecord = data.attendanceRecords.find(record => record.student_id === student.id);
    const attendance = attendanceRecord ? attendanceRecord.status : 'Absent';

    tbody.innerHTML += `
        <tr>
            <td>${index + 1}</td>
            <td>${student.full_name}</td>
            <td>${student.custom_id}</td>
            <td>${attendance}</td>
        </tr>
    `;
});

            // Update Summary
            document.getElementById('totalStudents').textContent = data.stats.total;
            document.getElementById('presentStudents').textContent = data.stats.present;
            document.getElementById('absentStudents').textContent = data.stats.absent;
            document.getElementById('onLeaveStudents').textContent = data.stats.onLeave;

            document.getElementById('attendanceTable').style.display = 'block';
            document.getElementById('attendanceSummary').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
});



          </script>
        <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/view-attendance.blade.php ENDPATH**/ ?>