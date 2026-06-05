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
                    <h4 class="card-title my-4">Edit Class</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> Class</h4>
                    </div>
                    <div class="card-body bg-light">
           
                        <form class="mt-4" action="<?php echo e(route('class.update', $class->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                        
                            <div class="mb-3">
                                <label for="examName" class="form-label">Class Name <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="examName" 
                                    name="name" 
                                    placeholder="Enter class name" 
                                    required 
                                    maxlength="255" 
                                    value="<?php echo e(old('name', $class->name)); ?>">
                                
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
                        
                            <div class="mb-3">
                                <label class="form-label">Select Subjects <span class="text-danger">*</span></label>
                                <div class="form-check ml-5">
                                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input <?php $__errorArgs = ['subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                type="checkbox" 
                                                name="subject_id[]" 
                                                value="<?php echo e($subject->id); ?>" 
                                                id="subject-<?php echo e($subject->id); ?>" 
                                                <?php echo e(in_array($subject->id, old('subject_id', $class->subjects->pluck('id')->toArray())) ? 'checked' : ''); ?>>
                                            
                                            <label class="form-check-label" for="subject-<?php echo e($subject->id); ?>">
                                                <?php echo e($subject->subj_name); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                    <?php $__errorArgs = ['subject_id'];
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
                            </div>
                        
                            <div class="mb-3">
                                <label for="teachers" class="form-label">Assign a Teacher to Class</label>
                                <select 
                                    class="form-control <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    name="teacher_id" 
                                    id="teacher-select"
                                >
                                    <option value="" disabled>Select a Teacher</option>
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option 
                                            value="<?php echo e($teacher->id); ?>" 
                                            <?php echo e(old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : ''); ?>>
                                            <?php echo e($teacher->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                
                                <?php $__errorArgs = ['teacher_id'];
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
                        
                            <div class="mb-3">
                                <label for="examNote" class="form-label">Note</label>
                                <textarea 
                                    class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="examNote" 
                                    rows="3" 
                                    name="note" 
                                    placeholder="Enter any notes" 
                                    maxlength="500"><?php echo e(old('note', $class->note)); ?></textarea>
                                
                                <?php $__errorArgs = ['note'];
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
                        
                            <button type="submit" class="btn" style="background-color: #084298; color: white;">Update Class</button>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/edit-class.blade.php ENDPATH**/ ?>