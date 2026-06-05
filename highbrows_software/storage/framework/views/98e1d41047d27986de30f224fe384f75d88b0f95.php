<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-5">
            <div class="card shadow-lg">
                <div class="card-header  text-white " style="background-color: #084298">
                    <h4>Admission Form</h4>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
<ul>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>

<form id="admissionForm" action="<?php echo e(route('admissions.store')); ?>" enctype="multipart/form-data" method="post">
<?php echo csrf_field(); ?>

<!-- Step 1 -->
<h5 class=" mb-3" style="color: #084298">Personal Information</h5>
<div class="row g-3">
    <div class="col-md-6">
        <input type="hidden" name="user_id" value="<?php echo e($user_id ?? ''); ?>">
        <label for="full_name">Full Name</label>
        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo e(old('full_name')); ?>">
        <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="father_name">Father Name</label>
        <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo e(old('father_name')); ?>">
        <?php $__errorArgs = ['father_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="mother_name">Mother Name</label>
        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?php echo e(old('mother_name')); ?>">
        <?php $__errorArgs = ['mother_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="student_cnic">Student B-Form Number</label>
        <input type="text" class="form-control" id="student_cnic" name="student_cnic" value="<?php echo e(old('student_cnic')); ?>">
        <?php $__errorArgs = ['student_cnic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="mother_cnic">Mother CNIC</label>
        <input type="text" class="form-control" id="mother_cnic" name="mother_cnic" value="<?php echo e(old('mother_cnic')); ?>">
        <?php $__errorArgs = ['mother_cnic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="father_cnic">Father CNIC</label>
        <input type="text" class="form-control" id="father_cnic" name="father_cnic" value="<?php echo e(old('father_cnic')); ?>">
        <?php $__errorArgs = ['father_cnic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<hr class="my-4">

<!-- Step 2 -->
<h5 class="mb-3" style="color: #084298">Additional Information</h5>
<div class="row g-3">
    <div class="col-md-6">
        <label for="guardian_name">Guardian Name (If father is deceased)</label>
        <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="<?php echo e(old('guardian_name')); ?>">
        <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="religion">Religion</label>
        <input type="text" class="form-control" id="religion" name="religion" value="<?php echo e(old('religion')); ?>">
        <?php $__errorArgs = ['religion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="pre_class">Previous Class</label>
        <input type="text" class="form-control" id="pre_class" name="pre_class" value="<?php echo e(old('pre_class')); ?>">
        <?php $__errorArgs = ['pre_class'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="grade_applied_for">Grade Applied For</label>
        <select class="form-control" id="grade_applied_for" name="grade_applied_for">
            <option value="">Select Grade</option>
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($class->id); ?>" <?php echo e(old('grade_applied_for') == $class->id ? 'selected' : ''); ?>>
                    <?php echo e($class->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['grade_applied_for'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="dob">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo e(old('dob')); ?>">
        <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="dob">Admission Date</label>
        <input type="date" class="form-control" id="admission_date" name="admission_date" value="<?php echo e(old('admission_date')); ?>">
        <?php $__errorArgs = ['admission_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="res_type">Residence Type</label>
        <select class="form-control" id="res_type" name="res_type">
            <option value="">Select</option>
            <option value="Online" <?php echo e(old('res_type') == 'Online' ? 'selected' : ''); ?>>Online</option>
            <option value="DayScholar" <?php echo e(old('res_type') == 'Day Scholar' ? 'selected' : ''); ?>>Day Scholar</option>
            <option value="Hostel" <?php echo e(old('res_type') == 'Hostelite' ? 'selected' : ''); ?>>Hostelite</option>
        </select>
        <?php $__errorArgs = ['res_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<hr class="my-4">

<!-- Step 3 -->
<h5 class=" mb-3" style="color: #084298">Contact Information</h5>
<div class="row g-3">
    <div class="col-md-6">
        <label for="contact">Father's Contact</label>
        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo e(old('contact')); ?>">
        <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="guardian_phone">Mother's Contact</label>
        <input type="text" class="form-control" id="guardian_phone" name="guardian_phone" value="<?php echo e(old('guardian_phone')); ?>">
        <?php $__errorArgs = ['guardian_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="guardian_whatsapp">WhatsApp Number</label>
        <input type="text" class="form-control" id="guardian_whatsapp" name="guardian_whatsapp" value="<?php echo e(old('guardian_whatsapp')); ?>">
        <?php $__errorArgs = ['guardian_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="guardian_contact">Guardian Contact (If father is deceased)</label>
        <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" value="<?php echo e(old('guardian_contact')); ?>">
        <?php $__errorArgs = ['guardian_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="domicile">Domicile District</label>
        <input type="text" class="form-control" id="domicile" name="domicile" value="<?php echo e(old('domicile')); ?>">
        <?php $__errorArgs = ['domicile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="postal_address">Postal Address</label>
        <textarea class="form-control" id="postal_address" name="postal_address"><?php echo e(old('postal_address')); ?></textarea>
        <?php $__errorArgs = ['postal_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="father_income">Father's Income</label>
        <input type="text" class="form-control" id="father_income" name="father_income" value="<?php echo e(old('father_income')); ?>">
        <?php $__errorArgs = ['father_income'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<hr class="my-4">

<!-- Step 4 -->
<h5 class="mb-3" style="color: #084298">Documents</h5>
<div class="row g-3">
    <div class="col-md-6">
        <label for="passport_pic">Passport Size Picture</label>
        <input type="file" class="form-control" id="passport_pic" name="passport_pic">
        <?php $__errorArgs = ['passport_pic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="b_form">Student's B-Form</label>
        <input type="file" class="form-control" id="b_form" name="b_form">
        <?php $__errorArgs = ['b_form'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="father_cnic_doc">Father CNIC (Document)</label>
        <input type="file" class="form-control" id="father_cnic_doc" name="father_cnic_doc">
        <?php $__errorArgs = ['father_cnic_doc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-md-6">
        <label for="result_card">Last Result Card</label>
        <input type="file" class="form-control" id="result_card" name="result_card">
        <?php $__errorArgs = ['result_card'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="text-danger"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<hr class="my-4">

<!-- Checkbox to Apply for Cadet Colleges -->
<h5 class=" mb-3" style="color: #084298">Cadet College Application</h5>
<div class="row g-3">
    <div class="col-md-12">
        <label>
            <input type="checkbox" id="apply_cadet_colleges" name="apply_cadet_colleges" value="1">
            Do you want to apply for Cadet Colleges?
        </label>
    </div>
</div>

<!-- Static List of Cadet Colleges (Initially Hidden) -->
<div id="cadetCollegesList" class="mt-3" style="display: none;">
    <label>Select the Cadet Colleges you want to apply for:</label>
    <div class="row g-3">
        <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6">
            <div class="form-check">
                <!-- College selection checkbox with value as the college ID -->
                <input class="form-check-input" type="checkbox" name="cadet_colleges[]" value="<?php echo e($college->id); ?>" id="college_<?php echo e($college->id); ?>">
                <label class="form-check-label" for="college_<?php echo e($college->id); ?>"><?php echo e($college->college_name); ?></label>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-lg" style="background-color: #084298; color:white">Submit</button>
</div>
</form>

                </div>
            </div>
        </div>
    </main>
</div>

</div>


    </div>
    <script>
document.getElementById('apply_cadet_colleges').addEventListener('change', function () {
const cadetCollegesList = document.getElementById('cadetCollegesList');
cadetCollegesList.style.display = this.checked ? 'block' : 'none';
});

    </script>
     <?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admissions.blade.php ENDPATH**/ ?>