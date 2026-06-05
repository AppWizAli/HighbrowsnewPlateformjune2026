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
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="<?php echo e(route('students.create')); ?>">Register Student<a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Registered Students</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Students List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="admissionsTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <tr>
                                                                                        <td><?php echo e($loop->iteration); ?></td>
                                                                                   
                                                                                        <td><?php echo e($student->name); ?></td>
                                                                                        <td><?php echo e($student->email); ?></td>
                                                                                        <td><?php echo e($student->contact); ?></td>
                                                                                        <td>
                                                                                            
                                                                                            <?php if(optional($student->admissions->first())->id): ?> 
                                                                                            
                                                                                            <a href="<?php echo e(route('admissions.show', $student->admissions->first()->id)); ?>" 
                                                                                               class="btn btn-info btn-sm" title="View">
                                                                                                <i class="fas fa-eye"></i>
                                                                                            </a>
                                                                                        <?php else: ?>
                                                                                            
                                                                                            <a href="<?php echo e(route('addstudent', ['user_id' => $student->id])); ?>" 
                                                                                               class="btn btn-success btn-sm" title="Add Admission">
                                                                                                <i class="fas fa-user-plus"></i>
                                                                                            </a>
                                                                                        <?php endif; ?>
                                                                                        
                                                                                        
                                                                                             
                                                                                             
                                                                                            
                                                                                             
                                                                                            <form action="<?php echo e(route('students.destroy', $student->id)); ?>"
                                                                                                method="POST" style="display:inline;">
                                                                                                <?php echo csrf_field(); ?>
                                                                                                <?php echo method_field('DELETE'); ?>
                                                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                                                    title="Delete" onclick="return confirm('Are you sure?')">
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/student-list.blade.php ENDPATH**/ ?>