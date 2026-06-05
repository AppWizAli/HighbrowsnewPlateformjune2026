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

    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <div class="d-flex justify-content-start">
                    <h4 class="card-title my-4">Add Result</h4>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> Results</h4>
                    </div>

                    <div class="card-body bg-light">
                        <form class="mt-1" action="<?php echo e(route('result-store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="row mt-3">
                                <div class="col-md-3 px-5">
                                    <label for="exam">Exam Name</label>
                                    <select class="form-control" name="exam" required>
                                        <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?></option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="class">Class Name</label>
                                    <select class="form-control" name="class" required>
                                        <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="subject">Subject</label>
                                    <select class="form-control" name="subject" required>
                                        <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->subj_name); ?></option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="row mt-4 px-5">
                                <div class="col-md-3">
                                    <label for="shared_total">Set Total Marks for All (optional):</label>
                                    <input type="number" id="shared_total" class="form-control">
                                </div>
                            </div>

                            
                            <?php if($students->isNotEmpty()): ?>
                                <div class="container-fluid bg-light mt-4 p-3" style="border-radius: 15px">
                                    <div class="row fw-bold">
                                        <div class="col-md-3 px-5">
                                            <label>Student</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Marks</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Obtained Marks</label>
                                        </div>
                                    </div>

                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row align-items-center mt-2">
                                            <div class="col-md-3 px-5">
                                                <input type="hidden" name="students[<?php echo e($loop->index); ?>][student_id]" value="<?php echo e($student->id); ?>">
                                                <input type="text" class="form-control" value="<?php echo e($student->full_name); ?>" disabled>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number" class="form-control total-input" name="students[<?php echo e($loop->index); ?>][total]" required>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="students[<?php echo e($loop->index); ?>][obt_marks]" required>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">Add Marks</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<script>
    document.getElementById('shared_total').addEventListener('input', function () {
        let total = this.value;
        document.querySelectorAll('.total-input').forEach(input => {
            input.value = total;
        });
    });
</script>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/students-results.blade.php ENDPATH**/ ?>