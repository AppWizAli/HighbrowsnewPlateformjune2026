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
            <div class="container">
                <div >
                    
              <h4 class="mt-3">Filter list </h4>
                        <form id="searchForm" method="GET" action="" class="mb-4">
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
    
                                  <select name="class" id="class" class="form-control">
                                    <option value="">Select Class</option>
    
                                    <!-- Populate classes dynamically -->
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <option value="<?php echo e($class->id); ?>"
                                          <?php echo e($class->id == request()->query('class') ? 'selected' : ''); ?>>
                                          <?php echo e($class->name); ?>

                                      </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                                </div>
                              </div>
                
    
                              <div class="col-md-4">
                                <div class="mt-1"></span>
                                <button type="submit" class="btn " style="background-color: #084298;color:white;">Search</button>
                              </div>
                            </div>
                          </form>
                 
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i>Result List</h4>
                            </div>
                            <?php if(session('message')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('message')); ?></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 class="card-title mb-4">Result List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="datatablesSimple">
                                        <thead>
                                          <tr>
                                            <th>Sr.no</th>
                                            <th>Student Name</th>
                                            <th>Class</th>
                                            <th>View</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $count = 0;
                                            ?>
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(++$count); ?></td>
                                                    <td><?php echo e($student->full_name); ?></td>
                                                    <td><?php echo e($student->grade->name); ?></td>
                                                 
                                                    <td>
                                                        <?php
                                                            $examFound = false;
                                                        ?>
                                                        <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($exam->class_id == $student->grade_applied_for): ?>
                                                                <a href="<?php echo e(route('result-view', ['id' => $student->id])); ?>" class="dropdown-item text-info" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <?php
                                                                    $examFound = true;
                                                                    break; // Exit the loop once an exam is found
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                                                        <?php if(!$examFound): ?>
                                                        <a href="<?php echo e(route('not_Found')); ?>" class="dropdown-item text-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                    
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                      </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    function filterTable(inputElement, columnIndex, suggestionListId) {
        const filter = inputElement.value.toLowerCase();
        const rows = document.querySelectorAll('#examsTable tbody tr');
        const suggestionList = document.getElementById(suggestionListId);
        suggestionList.innerHTML = '';
        let hasSuggestions = false;

        rows.forEach(row => {
            const cellText = row.querySelector(`td:nth-child(${columnIndex})`).textContent.toLowerCase();
            if (cellText.includes(filter)) {
                row.style.display = '';
                const suggestionItem = document.createElement('li');
                suggestionItem.className = 'list-group-item list-group-item-action';
                suggestionItem.textContent = cellText;
                suggestionItem.addEventListener('click', function() {
                    inputElement.value = cellText;
                    suggestionList.style.display = 'none';
                    rows.forEach(r => {
                        const name = r.querySelector(`td:nth-child(${columnIndex})`).textContent.toLowerCase();
                        r.style.display = name === cellText ? '' : 'none';
                    });
                });
                suggestionList.appendChild(suggestionItem);
                hasSuggestions = true;
            } else {
                row.style.display = 'none';
            }
        });

        suggestionList.style.display = hasSuggestions ? 'block' : 'none';
    }

    // Only filter by class now
    document.getElementById('filterClass').addEventListener('keyup', function() {
        filterTable(this, 3, 'classSuggestionList'); // Assuming the class column is the 3rd column
    });

    // Removed the section filter part

    document.addEventListener('click', function(event) {
        const inputElement = document.getElementById('filterClass');
        const suggestionList = document.getElementById(inputElement.nextElementSibling.id);
        if (!inputElement.contains(event.target)) {
            suggestionList.style.display = 'none';
        }
    });
</script>
<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/resultlist.blade.php ENDPATH**/ ?>