<div id="layoutSidenav_nav" style="background-color: white">
    <nav class="sb-sidenav accordion" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                
                <a class="nav-link" href="<?php echo e(route('cordinator.dashboard')); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link" href="<?php echo e(route('students.index')); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-reader"></i></div>
                    Registered Students
                </a>

                <a class="nav-link" href="<?php echo e(route('admissions.index')); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                    Admission Records
                </a>
                <a class="nav-link" href="<?php echo e(route('students_attendence')); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock-o"></i></div>
                    Attendance
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#examsMenu" aria-expanded="false" aria-controls="examsMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Exams
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="examsMenu" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                       
                        <a class="nav-link" href="<?php echo e(route('result')); ?>">Result</a>
                        <a class="nav-link" href="<?php echo e(route('result-list')); ?>">Result List</a>
                    </nav>
                </div>
                <a class="nav-link" href="<?php echo e(route('fees.index')); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                    Fees
                </a>

                
                <a class="nav-link" href="<?php echo e(route('monthlyfee.index')); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                  Monthly Fee
                </a>
            </div>
        </div>
    </nav>
</div>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/cordinator/sidebar.blade.php ENDPATH**/ ?>