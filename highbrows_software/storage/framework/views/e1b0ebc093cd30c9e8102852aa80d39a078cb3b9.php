<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
    #layoutSidenav {
    display: flex;
    width: 100%;
}

#layoutSidenav .sidebar {
    width: 250px;
}

main {
    flex: 1; 
    padding: 20px;
    background-color: #f8f9fa; 
}

</style>
<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div id="layoutSidenav" class="d-flex">
    <!-- Sidebar Section -->
    <div class="sidebar">
        <?php if(auth()->user()->usertype == 'admin'): ?>
        <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php elseif(auth()->user()->usertype == 'subadmin'): ?>
        <?php echo $__env->make('subadmin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
        <?php elseif(auth()->user()->usertype == 'cordinator'): ?>
        <?php echo $__env->make('cordinator.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php else: ?>
    <?php echo $__env->make('student.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php endif; ?> 
    </div>

    <!-- Main Content Section -->
    <main class="flex-grow-1">
        <div class="container-fluid px-4">
            <h3 class="mt-5">Welcome, <?php echo e(Auth::user()->name); ?></h3>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            
            <!-- Cards Layout -->
            <div class="row">
               
                  <div class="col-md-6"><img src="<?php echo e(asset('highbroimage/people.svg')); ?>" alt="img"  width="100%" class="mb-3" style="border-radius: 12px"></div>
                  <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4" style="background-color: #4747A1; color: white;">
                                <div class="card-body">Classes Assigned</div>
                                <div class="card-footer">
                                   <p><?php
    $teacher = \App\Models\Teacher::where('user_id', auth()->user()->id)->with('classes')->first();
?>

<?php if($teacher && $teacher->classes->isNotEmpty()): ?>
    <ul>
        <?php $__currentLoopData = $teacher->classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($class->name); ?> - <?php echo e($class->note); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php else: ?>
    <p>No classes assigned.</p>
<?php endif; ?></p>
                        
                                </div>
                            </div>
                            
                            
                        </div>
      <?php
    $statusData = \App\Models\Teacher::getStatusSummaryByUserId(auth()->user()->id);
?>
                       <div class="col-md-6">
    <div class="card mb-4" style="background-color: #8F8EED; color: white;">
        <div class="card-body">
            <strong>Salary Status</strong>
        </div>
        <div class="card-footer">
            <div><strong>Amount:</strong><?php echo e(number_format($statusData['salary'], 2)); ?> RS</div>
            <div><strong>Status:</strong> <?php echo e(ucfirst($statusData['salary_status'])); ?></div>
        </div>
    </div>
</div>

                   
                        
                      
                      
                        <div class="col-md-6">
                            <div class="card mb-4" style="background-color: #F59095; color: white;">
                                <div class="card-body">Todays Attendance</div>
                                <div class="card-footer"> <?php echo e(ucfirst($statusData['attendance_today'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <!-- Terms and Conditions Modal -->
              <?php if(session('show_terms_modal')): ?>
              <script>
                  document.addEventListener("DOMContentLoaded", function() {
                      var myModal = new bootstrap.Modal(document.getElementById('termsModal'), {
                          keyboard: false
                      });
                      myModal.show();
                  });
              </script>
              <?php echo e(session()->forget('show_terms_modal')); ?> <!-- Remove session after showing modal -->
          <?php endif; ?>
          
          <!-- Terms and Conditions Modal -->
          <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                          <?php if($terms): ?>
                              <?php echo $terms->rules; ?>

                          <?php else: ?>
                              <p>No terms and conditions available.</p>
                          <?php endif; ?>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Agree</button>
                      </div>
                  </div>
              </div>
          </div>
          

        </div>
    </main>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('termsModal'), {
            keyboard: false
        });
        myModal.show(); 
    });
    </script>
    
<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/subadmin/dashboard.blade.php ENDPATH**/ ?>