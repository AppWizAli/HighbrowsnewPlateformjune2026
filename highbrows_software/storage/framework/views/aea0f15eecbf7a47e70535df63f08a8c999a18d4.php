<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
             <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
            <h3 class="mt-4">Welcome, <?php echo e(Auth::user()->name); ?></h3>
            <?php if(request('month') || request('year')): ?>
    <div class="alert alert-info">
        Showing results for:
        <?php if(request('month')): ?> <strong><?php echo e(date('F', mktime(0, 0, 0, request('month'), 1))); ?></strong> <?php endif; ?>
        <?php if(request('year')): ?> <strong><?php echo e(request('year')); ?></strong> <?php endif; ?>
    </div>
<?php endif; ?>
            <form method="GET" class="row mb-4">
                <div class="col-md-3">
                    <select name="month" class="form-control">
                        <option value="">Select Month</option>
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo e(sprintf('%02d', $m)); ?>" <?php echo e(request('month') == sprintf('%02d', $m) ? 'selected' : ''); ?>>
                                <?php echo e(date('F', mktime(0, 0, 0, $m, 1))); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="year" class="form-control">
                        <option value="">Select Year</option>
                        <?php for($y = now()->year; $y >= 2020; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
                <div class="col-md-3">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
            
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">
            <div class="col-md-6"><img src="<?php echo e(asset('highbroimage/people.svg')); ?>" alt="img"  width="100%" class="mb-3" style="border-radius: 12px"></div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4" style="background-color: #4747A1; color: white;">
                            <div class="card-body">Income</div>
                            <div class="card-footer">
                             
                                <p>RS.</p><?php echo e(number_format($totalIncome, 2)); ?>


                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4" style="background-color: #8F8EED; color: white;">
                            <div class="card-body">Expenses</div>
                            <div class="card-footer">
                           <p>RS.</p> <?php echo e(number_format($totalExpenses, 2)); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4" style="background-color: #8F8EED; color: white;">
                            <div class="card-body">Revenue</div>
                            <div class="card-footer">
                              
                               <p>RS.</p> <?php echo e(number_format($revenue, 2)); ?>

                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="card mb-4" style="background-color: #96B2FB; color: white;">
                            <div class="card-body">Students</div>
                            <div class="card-footer"><?php echo e(number_format( $studentCount, 0)); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4" style="background-color: #F59095; color: white;">
                            <div class="card-body">Employees</div>
                            <div class="card-footer"><?php echo e(number_format($teacherCount, 0)); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            </div>


        </div>
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Powered By HiSkyTech Website 2025</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/main.blade.php ENDPATH**/ ?>