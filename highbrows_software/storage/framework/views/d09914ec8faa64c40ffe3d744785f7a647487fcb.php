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
                <div class="d-flex justify-content-start">
                    <h4 class="card-title my-4">Edit Teacher</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Teacher</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form action="<?php echo e(route('teachers.update', $employee->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-white" style="background-color: #084298;">
                                    <h4 class="mb-0">Update Employee Details</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Name Field -->
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input 
                                            type="text" 
                                            class="form-control <?php echo e($errors->has('name') ? 'is-invalid' : ''); ?>" 
                                            id="name" 
                                            name="name" 
                                            value="<?php echo e($employee->name); ?>" 
                                            required>
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                        
                                    <!-- Date of Birth Field -->
                                    <div class="form-group mb-3">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input 
                                            type="date" 
                                            class="form-control <?php echo e($errors->has('date_of_birth') ? 'is-invalid' : ''); ?>" 
                                            id="dob" 
                                            name="date_of_birth" 
                                            value="<?php echo e($employee->date_of_birth); ?>" 
                                            required>
                                        <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                        
                                    <!-- Gender Field -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Gender</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="radio" 
                                                    name="gender" 
                                                    id="genderMale" 
                                                    value="Male" 
                                                    <?php echo e($employee->gender == 'Male' ? 'checked' : ''); ?> 
                                                    required>
                                                <label class="form-check-label" for="genderMale">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="radio" 
                                                    name="gender" 
                                                    id="genderFemale" 
                                                    value="Female" 
                                                    <?php echo e($employee->gender == 'Female' ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="genderFemale">Female</label>
                                            </div>
                                        </div>
                                        <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                        
                                    <!-- Email Field -->
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input 
                                            type="email" 
                                            class="form-control <?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>" 
                                            id="email" 
                                            name="email" 
                                            value="<?php echo e($employee->email); ?>">
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                        
                                    <!-- Phone Field -->
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input 
                                            type="text" 
                                            class="form-control <?php echo e($errors->has('phone') ? 'is-invalid' : ''); ?>" 
                                            id="phone" 
                                            name="phone" 
                                            value="<?php echo e($employee->phone); ?>">
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                        
                                    <!-- Address Field -->
                                    <div class="form-group mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea 
                                            class="form-control <?php echo e($errors->has('address') ? 'is-invalid' : ''); ?>" 
                                            id="address" 
                                            name="address" 
                                            rows="3" 
                                            required><?php echo e($employee->address); ?></textarea>
                                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                        
                                    <!-- Joining Date Field -->
                                    <div class="form-group mb-3">
                                        <label for="joining_date" class="form-label">Joining Date</label>
                                        <input 
                                            type="date" 
                                            class="form-control <?php echo e($errors->has('joining_date') ? 'is-invalid' : ''); ?>" 
                                            id="joining_date" 
                                            name="joining_date" 
                                            value="<?php echo e($employee->joining_date); ?>">
                                        <?php $__errorArgs = ['joining_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                        
                                    <!-- Image Field -->
                                    <div class="form-group mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input 
                                            type="file" 
                                            class="form-control <?php echo e($errors->has('image') ? 'is-invalid' : ''); ?>" 
                                            id="image" 
                                            name="image" 
                                            accept="image/*">
                                        <div class="mt-2">
                                            <img 
                                                src=" <?php echo e(Storage::disk('public')->url($employee->image)); ?>" 
                                                alt="Employee Image" 
                                                class="img-thumbnail" 
                                                style="width: 100px; height: 100px;">
                                        </div>
                                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn " style="background-color: #084298;color:white;">Update</button>
                                </div>
                            </div>
                        </form>
                        
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/edit-employee.blade.php ENDPATH**/ ?>