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
                    
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Admissions Data</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Admissions List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Passport Pic</th>
                                                <th>Student Name</th>
                                                <th>Roll No</th>
                                                <th>DOB</th>
                                                <th>Address</th>
                                                <th>Father CNIC</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $admissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <tr>
                                                                                        <td><?php echo e($loop->iteration); ?></td>
                                                                                        <td>
                                                                                            <?php if($admission->passport_pic): ?>
                                                                                                                                            <?php 
                                                                                                                                                        $filePath = asset('storage/app/public/' . $admission->passport_pic); // Make sure the path is correct
                                                                                                $fileExtension = pathinfo($admission->passport_pic, PATHINFO_EXTENSION);
                                                                                                                                                    ?>
                                                                                                                                            <?php if($fileExtension == 'pdf'): ?>
                                                                                                                                                <a href=" <?php echo e(Storage::disk('public')->url($admission->passport_pic)); ?>" target="_blank"
                                                                                                                                                    class="btn btn-primary">View PDF</a>
                                                                                                                                                <br>
                                                                                                                                                <embed src="<?php echo e(Storage::disk('public')->url($admission->passport_pic)); ?>" type="application/pdf" width="100"
                                                                                                                                                    height="100">
                                                                                                                                                <br>
                                                                                                                                                <a href="<?php echo e(Storage::disk('public')->url($admission->passport_pic)); ?>" download class="btn btn-primary">Download
                                                                                                                                                    PDF</a>
                                                                                                                                            <?php else: ?>
                                                                                                                                                <img src="<?php echo e(Storage::disk('public')->url($admission->passport_pic)); ?>" alt="Passport Picture" width="100"
                                                                                                                                                    height="100">
                                                                                                                                            <?php endif; ?>
                                                                                            <?php else: ?>
                                                                                                N/A
                                                                                            <?php endif; ?>
                                                                                        </td>


                                                                                        <td><?php echo e($admission->full_name); ?></td>
                                                                                        <td><?php echo e($admission->custom_id); ?></td>
                                                                                        <td><?php echo e($admission->dob); ?></td>
                                                                                        <td><?php echo e($admission->postal_address); ?></td>
                                                                                        <td><?php echo e($admission->father_cnic); ?></td>
                                                                                        <td>
                                                                                            <a href="<?php echo e(route('admissions.edit', $admission->id)); ?>"
                                                                                                class="btn btn-warning btn-sm" title="Edit">
                                                                                                <i class="fas fa-edit"></i>
                                                                                            </a>
                                                                                            <a href="<?php echo e(route('admissions.show', $admission->id)); ?>"
                                                                                                class="btn btn-success btn-sm" title="View">
                                                                                                <i class="fas fa-eye"></i>
                                                                                            </a>
                                                                                            <a href="<?php echo e(route('admissions.print', $admission->id)); ?>"
                                                                                                class="btn btn-info btn-sm text-white" title="Print Admission Record">
                                                                                                <i class="fas fa-print"></i>
                                                                                             </a>
                                                                                             
                                                                                            <form action="<?php echo e(route('admissions.destroy', $admission->id)); ?>"
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/students.blade.php ENDPATH**/ ?>