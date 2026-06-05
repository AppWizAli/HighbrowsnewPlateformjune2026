<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
  @media print {
    .btn{
      display: none;
    }
    .hide{
      display: none
    }
    .table {
      width: 100% !important;
    }
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
            <div class="container">
                <div class="d-flex justify-content-start">
                    
                    <button class="btn  mb-3" onclick="window.print()" style="background-color: #084298;color:white;">
                      <i class="fas fa-print"></i> Print Datesheet
                  </button>
                  
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> DateSheet</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                
                                <div class="table-responsive">
                               
                                    <table class="table table-striped table-bordered" id="examsTable">
                                        <thead>
                                          <tr>
                                            <th>Sr.no</th>
                                            <th>Subject</th>
                                            <th>Time</th>
                                            <th>Date</th>
                                            <th class="hide">Actions</th>
                                          </tr>
                                        </thead>
                    
                                        <tbody>
                                            <?php
                                                $count = 0;
                                            ?>
                                          <?php $__currentLoopData = $datesheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <?php if($exams->id == $exam->exam_schedule_id ): ?>
                                          <tr>
                                            <td><?php echo e(++$count); ?></td>
                    
                                            <td><?php echo e($exam->subject->subj_name); ?></td>
                                            <td><?php echo e($exam->start_time); ?> to <?php echo e($exam->end_time); ?></td>
                                            <td><?php echo e($exam->date); ?></td>
                                            <td class="hide">
                                               <!-- Edit Button -->
                                               <a href="<?php echo e(route('exam-schedule-date-edit', ['id' => $exam->id ])); ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                              </a>
                                              <form action="<?php echo e(route('exam-schedule-date_delete', ['id' => $exam->id])); ?>" method="POST" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                  <i class="fas fa-trash"></i>
                                                </button>
                                              </form>
                                            </td>
                                          </tr>
                                          <?php endif; ?>
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/datesheetlist.blade.php ENDPATH**/ ?>