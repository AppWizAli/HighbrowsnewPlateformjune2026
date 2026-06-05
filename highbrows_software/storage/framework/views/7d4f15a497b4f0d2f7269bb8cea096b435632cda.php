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
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="<?php echo e(route('schedule.create')); ?>">Add New Schedule</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i>Exams Schedule List</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Schedule List</h4>
                                <div class="table-responsive">
                                    <table class="table  table-bordered text-center" id="datatablesSimple">
                                        <thead>
                                          <tr>
                                            <th>Sr.no</th>
                                            <th>Exam Name</th>
                                            <th>Class</th>
                                      
                                            <th>Date</th>
                                            <th>More</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $count = 0;
                                            ?>
                                          <?php $__currentLoopData = $examschedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <tr>
                                            <td><?php echo e(++$count); ?></td>
                    
                                            <td>
                                                <?php echo e($exam->exam->name); ?>

                                            </td>
                                            <td>
                                               <?php echo e($exam->class->name); ?>

                    
                                            </td>
                                         
                                            <td><?php echo e($exam->start_date); ?> to <?php echo e($exam->end_date); ?></td>
                    
                                            <td>
                                                <!-- Dropdown -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i> <!-- More options icon -->
                                                        
                                                    </button>
                                                    <ul class="dropdown-menu" style=" inset: auto !important; right: 0 !important;top: 20px !important;">
                                                        <!-- Add Button -->
                                                 
                                                        <li>
                                                            <a href="<?php echo e(route('date-sheet',['id'=>$exam->id])); ?>" class="dropdown-item text-primary" title="Add">
                                                                <i class="fas fa-plus"></i> Add DateSheet
                                                            </a>
                                                        </li>
                                                        <!-- View Button -->
                                                        <li>
                                                            <a href="<?php echo e(route('date-sheet-list',['id' => $exam->id])); ?>" class="dropdown-item text-info" title="View">
                                                                <i class="fas fa-eye"></i> View DateSheet
                                                            </a>
                                                        </li>
                                                        <li>

                                                            <a class="dropdown-item text-primary" id="printResultBtn" title="Print" href="<?php echo e(route('exam-result',['id'=>$exam->id])); ?>"> <i class="fas fa-print"></i> Print Result</a>
                    
                    
                                                        </li>
                                                        <!-- Edit Button -->
                                                        <li>
                                                            <a href="<?php echo e(route('schedule.edit', ['id' => $exam->id])); ?>" class="dropdown-item text-warning" title="Edit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        </li>
                                                        <!-- Delete Button -->
                                                        <li>
                                                            <form action="<?php echo e(route('schedule.destroy', ['id' => $exam->id])); ?>" method="POST" style="display:inline;">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="dropdown-item text-danger" title="Delete">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/schedule-list.blade.php ENDPATH**/ ?>