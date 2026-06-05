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
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Monthly Fee</h4>
                            </div>

                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>

                            <div class="card-body">
                                <h4 class="card-title mb-4">Filter Fees</h4>

                                <!-- Filtering Form -->
                                <form method="GET" action="<?php echo e(route('studentmonthlyfee.index')); ?>" class="d-flex justify-content-end mb-3">
                                    <div class="me-2">
                                        <label for="month" class="form-label">Select Month:</label>
                                        <select name="month" id="month" class="form-select">
                                            <option value="">All</option>
                                            <?php for($m = 1; $m <= 12; $m++): ?>
                                                <option value="<?php echo e(str_pad($m, 2, '0', STR_PAD_LEFT)); ?>"
                                                    <?php echo e(request('month') == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''); ?>>
                                                    <?php echo e(date('F', mktime(0, 0, 0, $m, 1))); ?>

                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                    <div class="me-2">
                                        <label for="year" class="form-label">Select Year:</label>
                                        <select name="year" id="year" class="form-select">
                                            <option value="">All</option>
                                            <?php for($y = now()->year; $y >= now()->year - 5; $y--): ?>
                                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
                                                    <?php echo e($y); ?>

                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                    <div class="align-self-end">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>

                                <!-- Show Table Only When Data is Available -->
                                <?php if(request()->filled('month') || request()->filled('year') || $monthlyFees): ?>
                                    <?php if($monthlyFees->count() > 0): ?>
                                        <h4 class="card-title mb-4">Fee List</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped text-center" id="datatablesSimple">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Receipt No.</th>
                                                        <th>Total Amount</th>
                                                        <th>Month</th>
                                                        <th>Pay</th>
                                                        <th>status by Admin</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $monthlyFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthlyFee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($monthlyFee->id); ?></td>
                                                            <td> <?php echo e(optional($monthlyFee->student)->full_name ?? $monthlyFee->user->name ?? 'N/A'); ?></td>
                                                            <td><?php echo e($monthlyFee->receipt_no); ?></td>
                                                            <td><?php echo e(number_format($monthlyFee->total_amount, 2)); ?></td>
                                                            <td><?php echo e($monthlyFee->fee_month); ?></td>

                                                            <td>


                                                                <!-- Single Receipt Upload Form for Monthly Fee -->
                                                                <form action="<?php echo e(route('monthlyfee.upload', $monthlyFee->id)); ?>" method="POST" enctype="multipart/form-data" class="mt-2">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="input-group">
                                                                        <input type="file" name="receipt" class="form-control" required>
                                                                        <button type="submit" class="btn btn-primary">Upload</button>
                                                                    </div>
                                                                </form>

                                                                <!-- Show Uploaded Receipt -->
                                                                <?php if($monthlyFee->recepit): ?>
                                                                    <a href="<?php echo e(asset('storage/receipts/' . $monthlyFee->recepit)); ?>" target="_blank" class="btn btn-success btn-sm mt-2">
                                                                        View Receipt
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($monthlyFee->status == 'pending'): ?>
                                                                <!-- Pay Button -->
                                                                <span class="badge bg-danger">Unpaid</span>
                                                            <?php elseif($monthlyFee->status == 'paid'): ?>
                                                                <!-- Paid Status -->
                                                                <span class="badge bg-success">Paid</span>
                                                            <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(route('monthlyfee.show', $monthlyFee->id)); ?>"
                                                                    class="btn btn-info btn-sm text-white" title="Print Admission Record">
                                                                    <i class="fas fa-print"></i>
                                                                </a>


                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning mt-3">
                                            No records found for the selected month and year.
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-info mt-3">
                                        Please apply filters to search for fees.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/student/monthlyfee.blade.php ENDPATH**/ ?>