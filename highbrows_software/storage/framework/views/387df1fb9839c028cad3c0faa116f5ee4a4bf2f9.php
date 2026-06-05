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
                        <h2>Attandance Detail</h2>
                    </div>
                    <div class="card-body">
                        

<div class="row mt-5">

    
  <div class="col-md-4 mt-4 text-center">
      <div class="card border-0 shadow">
        <div class="card-body">
          <img src="<?php echo e('storage/app/public/'.$employee->image); ?>" class="card-img-top rounded-circle mx-auto mt-4" alt="Employee Image" style="width: 150px; border: 5px solid #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
          <h5 class="card-title text-uppercase" style="font-size: 15px; font-weight: bold; margin-top: 10px;"><?php echo e($employee->name); ?></h5>

          <ul class="list-group text-left">
            <li class="list-group-item py-2 border-bottom" style="font-size: 12px;">Gender: <?php echo e($employee->gender); ?></li>
            <li class="list-group-item py-2 border-bottom" style="font-size: 12px;">Date of Birth: <?php echo e(\Carbon\Carbon::parse($employee->dob)->format('d M Y')); ?></li>
            <li class="list-group-item py-2" style="font-size: 12px;">Phone: <?php echo e($employee->phone); ?></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-8 p-5">
      <h5 class="mx-5">Attendance Summary:</h5>
      <canvas id="myChart" class="w-75 h-auto mx-5"></canvas>
  </div>
  
 
     
<!-- Month and Year Filter Form -->
<div class="col-md-12 mt-5">
  <h5 class="card-title" style="font-size: 16px; margin-bottom: 10px;">
    Attendance for <?php echo e($months[$selectedMonth]); ?> <?php echo e($selectedYear); ?>

  </h5>
<form method="GET" action="<?php echo e(route('show_employee_attendace', $employee->id)); ?>" class="mb-3">
  <div class="row">
      <div class="col-md-4">
          <select name="month" class="form-control">
              <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($key); ?>" <?php echo e($key == $selectedMonth ? 'selected' : ''); ?>>
                      <?php echo e($month); ?>

                  </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
      </div>
      <div class="col-md-4">
          <select name="year" class="form-control">
              <?php for($year = now()->year; $year >= 2000; $year--): ?>
                  <option value="<?php echo e($year); ?>" <?php echo e($year == $selectedYear ? 'selected' : ''); ?>>
                      <?php echo e($year); ?>

                  </option>
              <?php endfor; ?>
          </select>
      </div>
      <div class="col-md-4">
          <button type="submit" class="btn " style="background-color: #084298;color:white;">Filter</button>
      </div>
  </div>
</form>


<!-- Attendance Table -->
<table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse; ">
<thead>
  <tr>
      <th style="width: 15px; font-size: 12px; background-color: #f7f7f7;" class="text-center">Date</th>
      <?php $__currentLoopData = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayOfWeek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <th style="width: 15px; font-size: 12px; background-color: #f7f7f7;" class="text-center"><?php echo e($dayOfWeek); ?></th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tr>
</thead>
<tbody>
  <?php for($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear); $day++): ?>
      <?php $date = $selectedYear . '-' . $selectedMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT); ?>
      <tr>
          <td style="width: 15px; font-size: 12px; background-color: #f7f7f7;" class="text-center"><?php echo e(date('d/m/Y', strtotime($date))); ?></td>
          <?php $__currentLoopData = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayOfWeek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                  $status = isset($attendanceData[$dayOfWeek][$day]) ? $attendanceData[$dayOfWeek][$day] : '';
              ?>
              <td class="text-center status-cell" style="width: 15px; font-size: 10px;">
                  <?php echo e($status); ?>

              </td>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tr>
  <?php endfor; ?>
</tbody>
</table>

     
    </div>
 
  </div> 

                    </div>
                </div>
            </div>
        </main>
    </div>
    
</div>


        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Color the status cells
            const statusCells = document.querySelectorAll('.status-cell');
            statusCells.forEach(cell => {
                const status = cell.textContent.trim();
                switch(status) {
                    case 'P': cell.style.backgroundColor = '#2a9d13'; break; // Present
                    case 'A': cell.style.backgroundColor = '#dd0e0e'; break; // Absent
                    case 'L': cell.style.backgroundColor = 'orange'; break; // Late
                    case 'LV': cell.style.backgroundColor = 'yellow'; break; // Leave
                    case 'EL': cell.style.backgroundColor = 'coral'; break; // Excused Late
                    default: cell.style.backgroundColor = ''; 
                }
            });
        
            // Chart.js - Doughnut Chart
            var ctx = document.getElementById("myChart").getContext("2d");
        
            var myChart = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: ["Present", "Absent", "Leave", "Late", "Excused Late"],
                    datasets: [{
                        label: "Attendance Count",
                        data: [
                            <?= $totalPresent ?? 0 ?>, 
                            <?= $totalAbsent ?? 0 ?>, 
                            <?= $totalLeave ?? 0 ?>, 
                            <?= $totalLate ?? 0 ?>, 
                            <?= $totalLateExcuse ?? 0 ?>
                        ],
                        backgroundColor: ["#2a9d13", "#dd0e0e", "yellow", "orange", "coral"],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
        </script>
        
        <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/attendance/employeeAttendance.blade.php ENDPATH**/ ?>