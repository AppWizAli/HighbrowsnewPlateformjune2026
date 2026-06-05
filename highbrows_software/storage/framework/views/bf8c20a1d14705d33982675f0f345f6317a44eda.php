

<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div id="layoutSidenav">
    <?php echo $__env->make(auth()->user()->usertype === 'admin' ? 'admin.sidebar' : (auth()->user()->usertype === 'subadmin' ? 'subadmin.sidebar' : (auth()->user()->usertype === 'cordinator' ? 'cordinator.sidebar' : 'student.sidebar')), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <style>
        .blue {
        background-color: #007bff;
        color: white;
    }
    </style>
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <h4>Edit Expense</h4>

                <form action="<?php echo e(route('expenses.update', $expense->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="<?php echo e(\Carbon\Carbon::parse($expense->date)->format('Y-m-d')); ?>" required>
                    </div>

                    <table class="table table-bordered" id="expensesTable">
                        <thead class="blue">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Amount (Rs)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="expensesBody">
                            <?php $__currentLoopData = $expense->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <input type="text" name="expenses[<?php echo e($index); ?>][description]" class="form-control" value="<?php echo e($desc['description']); ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="expenses[<?php echo e($index); ?>][amount]" class="form-control expense-amount" value="<?php echo e($desc['amount']); ?>" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td><input type="text" name="total" id="expensesTotal" class="form-control" value="<?php echo e($expense->total); ?>" readonly></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn btn-success mb-3" id="addExpenseRow">Add Row</button>
                    <br>
                    <button type="submit" class="btn btn-primary">Update Expense</button>
                </form>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowCount = <?php echo e(count($expense->details ?? [])); ?>;

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.expense-amount').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('expensesTotal').value = total.toFixed(2);
        }

        document.getElementById('addExpenseRow').addEventListener('click', function () {
            rowCount++;
            const newRow = `
                <tr>
                    <td>${rowCount}</td>
                    <td><input type="text" name="expenses[${rowCount}][description]" class="form-control" required></td>
                    <td><input type="number" name="expenses[${rowCount}][amount]" class="form-control expense-amount" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                </tr>`;
            document.getElementById('expensesBody').insertAdjacentHTML('beforeend', newRow);
        });

        document.getElementById('expensesBody').addEventListener('input', function (e) {
            if (e.target.classList.contains('expense-amount')) {
                updateTotal();
            }
        });

        document.getElementById('expensesBody').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                updateTotal();
            }
        });

        updateTotal();
    });
</script>
<?php echo $__env->make('admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/admin/expenses/edit.blade.php ENDPATH**/ ?>