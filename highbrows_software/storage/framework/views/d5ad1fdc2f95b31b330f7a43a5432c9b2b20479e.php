<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
    .student-info {
        text-align: center;
        margin-bottom: 20px;
    }
    .student-info img {
        border-radius: 50%;
        width: 100px;
        height: 100px;
    }
    .student-info .details {
        margin-top: 10px;
    }
    .result-table th, .result-table td {
        text-align: center;
        vertical-align: middle;
    }

    .result-summary {
background-color: #f8f9fa;
padding: 10px;
border-radius: 5px;
text-align: center;
}

.card {
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
border: none;
}
</style>
<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
<div id="layoutSidenav">
    <?php if(auth()->user()->usertype == 'admin'): ?>
        <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <!-- Include the Admin Sidebar -->
    <?php elseif(auth()->user()->usertype == 'subadmin'): ?>
        <?php echo $__env->make('subadmin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <!-- Include the Subadmin Sidebar -->
    <?php else: ?>
    <?php echo $__env->make('student.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php endif; ?>

    <!-- Container for the form, adjusted to the right of the sidebar -->
    <div id="layoutSidenav_content">
        <main>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="container">
                        <!-- Add New Exam Button -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <form id="searchForm" method="GET" action="" class="mb-4 w-100">
                                        <div class="form-group">
                                            <select name="exam" id="exam" class="form-control w-50">
                                                <option value="" disabled selected>Select Exam</option>
                                                <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($exam->id); ?>" <?php echo e($exam->id == request()->query('exam') ? 'selected' : ''); ?>>
                                                        <?php echo e($exam->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
                                            <!-- Hidden Student ID -->
                                            <button type="submit"
                                                class="btn btn-light btn-outline-primary mt-3">Search</button>
                                        
                                        </div>

                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- Filter Input -->
                    <div class="container ">
                        <div class="row">
                            <!-- Student Info -->
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <img src="<?php echo e(Storage::disk('public')->url( $student->passport_pic)); ?>" class="rounded-circle"
                                        alt="Student Image" height="150px">
                                    <h4 class="mt-2"><?php echo e($student->name); ?></h4>
                                    <p>Registration No: <?php echo e($student->custom_id); ?></p>
                                    <p>Class: <?php echo e($student->grade->name); ?></p>
                       
                                </div>
                            </div>
                            <!-- Result Table -->
                            <!-- Result Table -->
                            <div class="card ml-5">

                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Obtained Marks</th>
                                                <th>Total Marks</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $totalmarks = 0;
                                                $obt = 0;
                                                $avg = 0;
                                            ?>
                                        
                                            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="bg-white">
                                                    <td><?php echo e($result->subject->subj_name); ?></td>
                                                    <td><?php echo e($result->obt_marks); ?></td>
                                                    <td><?php echo e($result->total); ?></td>
                                                    <td><?php echo e($result->grade); ?></td>
                                                </tr>
                                                <?php
                                                    $totalmarks += $result->total;
                                                    $obt += $result->obt_marks;
                                                ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                            <?php
                                                $avg = $totalmarks > 0 ? ($obt / $totalmarks * 100) : 0;
                                            ?>
                                        
                                            <?php if($totalmarks == 0): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-danger">Exam is not attempted yet</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        
                                    </table>
                                    <div class="result-summary mt-4">
                                        <p>Total Marks: <?php echo e($totalmarks); ?></p>
                                        <p>Total Obtained Marks: <?php echo e($obt); ?></p>
                                        <p>Percentage: <?php echo e(number_format($avg, 2)); ?>%</p>
                                    </div>





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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/view-result.blade.php ENDPATH**/ ?>