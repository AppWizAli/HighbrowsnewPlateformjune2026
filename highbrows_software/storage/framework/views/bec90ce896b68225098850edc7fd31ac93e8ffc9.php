<div id="layoutSidenav_nav" style="background-color: white">
    <nav class="sb-sidenav accordion " id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
         
                <a class="nav-link" href="<?php echo e(route('subadmin.dashboard')); ?>" >
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="<?php echo e(route('subadmin.profile')); ?>" >
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    View Profile
                </a>
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#termsModal">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                    Terms & Conditions
                </a>
                
                <a class="nav-link" href="<?php echo e(route('students_attendence')); ?>" >
                    <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
           Manage Student Attendance
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
  Exams
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                
                        <a class="nav-link" href="<?php echo e(route('result')); ?>">Result</a>
                        <a class="nav-link" href="<?php echo e(route('result-list')); ?>">Result List</a>
                    </nav>
                </div>
            
            </div>
        </div>

    </nav>
</div><?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/subadmin/sidebar.blade.php ENDPATH**/ ?>