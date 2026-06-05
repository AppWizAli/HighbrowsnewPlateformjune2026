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
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Student Profile</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                          
                            <div class="card-body">
                                <div class="text-center mb-4"><img src="<?php echo e(Storage::disk('public')->url( $student->passport_pic)); ?>" alt="Student Image" class="img-fluid employee-image mt-5 " style="max-width: 150px;">
                                    <div class="employee-name"><?php echo e($student->full_name); ?> | <?php echo e($student->custom_id); ?></div></div>
                                    
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                       
                                        <tr>
                                            <th>Father's Name</th>
                                            <td><?php echo e($student->father_name); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mother's Name</th>
                                            <td><?php echo e($student->mother_name); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Student CNIC</th>
                                            <td><?php echo e($student->student_cnic); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Father CNIC</th>
                                            <td><?php echo e($student->father_cnic); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mother CNIC</th>
                                            <td><?php echo e($student->mother_cnic); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Guardian Name</th>
                                            <td><?php echo e($student->guardian_name ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Religion</th>
                                            <td><?php echo e($student->religion); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Previous Class</th>
                                            <td><?php echo e($student->pre_class); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Grade Applied For</th>
                                            <td><?php echo e($student->grade ? $student->grade->name : 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td><?php echo e($student->dob); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Admission Date</th>
                                            <td><?php echo e($student->admission_date); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Residential Type</th>
                                            <td><?php echo e($student->res_type); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number</th>
                                            <td><?php echo e($student->contact); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Guardian Phone</th>
                                            <td><?php echo e($student->guardian_phone); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Guardian WhatsApp</th>
                                            <td><?php echo e($student->guardian_whatsapp ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Guardian Contact</th>
                                            <td><?php echo e($student->guardian_contact ?? 'N/A'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Domicile</th>
                                            <td><?php echo e($student->domicile); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Postal Address</th>
                                            <td><?php echo e($student->postal_address); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Father's Income</th>
                                            <td><?php echo e(number_format($student->father_income, 2)); ?></td>
                                        </tr>
                                
                                        <!-- Display Applied Cadet Colleges -->
                                        <tr>
                                            <th>Applied Cadet Colleges</th>
                                            <td>
                                                <?php if($student->apply_cadet_colleges): ?>
                                                    <?php if($student->cadetColleges->isEmpty()): ?>
                                                        <span class="text-muted">No cadet colleges applied</span>
                                                    <?php else: ?>
                                                        <ul>
                                                            <?php $__currentLoopData = $student->cadetColleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li><?php echo e($college->college_name); ?></li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not applied for cadet colleges</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                
                                        <!-- Display Uploaded Documents -->
                                    
                                        <tr>
                                            <th>B-Form</th>
                                            <td>
                                                <?php if($student->b_form): ?>
                                                    <a href="<?php echo e(asset('storage/' . $student->b_form)); ?>" target="_blank">View B-Form</a>
                                                <?php else: ?>
                                                    <span class="text-muted">No B-Form</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Father CNIC Document</th>
                                            <td>
                                                <?php if($student->father_cnic_doc): ?>
                                                    <a href="<?php echo e(asset('storage/' . $student->father_cnic_doc)); ?>" target="_blank">View CNIC Document</a>
                                                <?php else: ?>
                                                    <span class="text-muted">No CNIC Document</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Result Card</th>
                                            <td>
                                                <?php if($student->result_card): ?>
                                                    <a href="<?php echo e(asset('storage/' . $student->result_card)); ?>" target="_blank">View Result Card</a>
                                                <?php else: ?>
                                                    <span class="text-muted">No Result Card</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
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
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/student-detail.blade.php ENDPATH**/ ?>