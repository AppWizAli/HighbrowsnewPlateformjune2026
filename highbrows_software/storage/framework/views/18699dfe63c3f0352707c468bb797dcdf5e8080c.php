<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
    #layoutSidenav {
    display: flex;
    width: 100%;
}

#layoutSidenav .sidebar {
    width: 250px;
}

main {
    flex: 1; 
    padding: 20px;
    background-color: #f8f9fa; 
}

</style>
<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div id="layoutSidenav" class="d-flex">
    <!-- Sidebar Section -->
    <div class="sidebar">
        <?php if(auth()->user()->usertype == 'admin'): ?>
        <?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php elseif(auth()->user()->usertype == 'subadmin'): ?>
        <?php echo $__env->make('subadmin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
        <?php elseif(auth()->user()->usertype == 'cordinator'): ?>
        <?php echo $__env->make('cordinator.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php else: ?>
    <?php echo $__env->make('student.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    <?php endif; ?> 
    </div>

    <!-- Main Content Section -->
    <main class="flex-grow-1">
        <div class="container-fluid px-4">
            <h3 class="mt-5">Welcome, <?php echo e(Auth::user()->name); ?></h3>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            
            <!-- Cards Layout -->
            <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6"><img src="<?php echo e(asset('highbroimage/people.svg')); ?>" alt="img"  width="100%" class="mb-3" style="border-radius: 12px"></div>
                </div>
              </div>
        </div>
    </main>
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/cordinator/dashboard.blade.php ENDPATH**/ ?>