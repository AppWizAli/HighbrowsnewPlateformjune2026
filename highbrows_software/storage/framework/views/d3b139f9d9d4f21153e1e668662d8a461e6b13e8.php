<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <!-- Sidebar Section -->
    <div id="layoutSidenav">
        <?php if(auth()->user()->usertype == 'admin'): ?>
    <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <!-- Include the Admin Sidebar -->
<?php elseif(auth()->user()->usertype == 'subadmin'): ?>
    <?php echo $__env->make('subadmin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <!-- Include the Subadmin Sidebar -->
<?php else: ?>
<?php echo $__env->make('student.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
    

    <!-- Main Content Section -->
    <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <?php if(session('message')): ?>
                                <div class="alert alert-danger mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
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
                                <div class="card-body">Marks</div>
                                <div class="card-footer">
                                    <?php
                                    
                                   $admissionId = \App\Models\Admission::where('user_id', Auth::id())
    ->latest('id')
    ->value('id');
$latestExamId = \App\Models\Result::where('student_id', $admissionId)
    ->orderByDesc('exam_id')
    ->value('exam_id');

// Step 2: Get all results for that exam
$marks = \App\Models\Result::with(['subject', 'exam'])
    ->where('student_id', $admissionId)
    ->where('exam_id', $latestExamId)
    ->get();
 $keywords = ['math', 'english', 'urdu'];
$filteredResults = $marks->filter(function ($result) use ($keywords) {
    return collect($keywords)->contains(fn($kw) =>
        str_contains(strtolower($result->subject->subj_name), $kw)
    );
});
$subjectWise = $filteredResults->map(function ($result) {
    return [
        'subject' => $result->subject->subj_name,
        'obtained_marks' => $result->obt_marks,
        'total_marks' => $result->total, // assuming this exists
    ];
});

        ?>
        <p><?php echo e($subjectWise[0]['subject'] ?? 'Math'); ?>:<?php echo e(number_format($subjectWise[0]['obtained_marks'] ?? 0,0)); ?>/<?php echo e(number_format($subjectWise[0]['total_marks'] ?? 0,0)); ?><br>
                                    <?php echo e($subjectWise[1]['subject'] ?? 'English'); ?>: <?php echo e(number_format($subjectWise[1]['obtained_marks'] ?? 0, 0)); ?>/<?php echo e(number_format($subjectWise[1]['total_marks'] ?? 0, 0)); ?><br>
                                    <?php echo e($subjectWise[2]['subject'] ?? 'Urdu'); ?>: <?php echo e(number_format($subjectWise[2]['obtained_marks'] ?? 0, 0)); ?>/<?php echo e(number_format($subjectWise[2]['total_marks'] ?? 0, 0)); ?></p>

                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4" style="background-color: #8F8EED; color: white;">
                                <div class="card-body"> This Month's Attendance (till Today)</div>
                                <div class="card-footer">             <?php
                                    $total = \App\Models\StudentAttendance::calculateMonthlyAttendance(Auth::user()->id);

                                ?>
                                <?php echo e(number_format($total['present'],0)); ?>/<?php echo e(number_format($total['total'],0)); ?></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4" style="background-color: #F59095; color: white;">
                                <div class="card-body">Monthly Fee Status</div>
                                <?php
                                use Carbon\Carbon;
                    $currentMonth = Carbon::now()->format('Y-m'); // e.g., "2025-04"

$monthlyFee = \App\Models\MonthlyFee::where(function ($query) {
    $query->where('student_id', Auth::user()->id)
          ->orWhere('user_id', Auth::user()->id);
})->where('created_at', 'like', $currentMonth . '%')->first();

$status = $monthlyFee ? $monthlyFee->status : 'pending';
                                ?>
                                <div class="card-footer"><?php echo e($status); ?></div>
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
            </div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('termsModal'), {
            keyboard: false
        });
        myModal.show(); // Auto open on page load
    });
    </script>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/student/index.blade.php ENDPATH**/ ?>