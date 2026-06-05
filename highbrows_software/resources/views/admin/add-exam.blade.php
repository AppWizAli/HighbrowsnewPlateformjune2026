@include('admin.head')
@include('admin.nav')
<div id="layoutSidenav">
    @if(auth()->user()->usertype == 'admin')
    @include('admin.sidebar')
@elseif(auth()->user()->usertype == 'subadmin')
    @include('subadmin.sidebar')
    @elseif(auth()->user()->usertype == 'cordinator')
    @include('cordinator.sidebar')
@else
@include('student.sidebar')
@endif

    <!-- Container for the form, adjusted to the right of the sidebar -->
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <div class="d-flex justify-content-start">
                    <h4 class="card-title my-4">Add New Exam</h4>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Exam</h4>

                    </div>
                    <div class="card-body bg-light">

                        <form class="mt-4" action="{{route('exams.store')}}" method="POST" >
                            @csrf
                            <div class="mb-3">
                                <label for="examName" class="form-label">Exam Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="examName" name="name" placeholder="Enter exam name" required>
                            </div>
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="Date" class="form-control" id="date" name="date" placeholder="" required>
                            </div>

                            <div class="mb-3">
                                <label for="examNote" class="form-label">Note</label>
                                <textarea class="form-control" id="examNote" rows="3" name="note" placeholder="Enter any notes"></textarea>
                            </div>
                            <button type="submit" class="btn " style="background-color: #084298;color:white;">Add Exam</button>
                        </form>



                    </div>
                </div>
            </div>

        </main>
    </div>

</div>


        </div>
<script>function createInstallmentFields() {
    const numInstallments = document.getElementById('installments').value;

    const container = document.getElementById('installment-container');
    container.innerHTML = ''; // Clear existing fields

    if (numInstallments && numInstallments > 0) {
        for (let i = 1; i <= numInstallments; i++) {
            const card = document.createElement('div');
            card.classList.add('card', 'mb-3', 'p-3');

            const cardHeader = document.createElement('h5');
            cardHeader.classList.add('card-title');
            cardHeader.innerText = 'Installment ' + i;
            card.appendChild(cardHeader);

            const row = document.createElement('div');
            row.classList.add('row');

            const amountCol = document.createElement('div');
            amountCol.classList.add('col-md-6', 'mb-3');
            const labelAmount = document.createElement('label');
            labelAmount.setAttribute('for', 'installment_amount_' + i);
            labelAmount.classList.add('form-label');
            labelAmount.innerText = 'Amount';
            amountCol.appendChild(labelAmount);
            const inputAmount = document.createElement('input');
            inputAmount.setAttribute('type', 'number');
            inputAmount.setAttribute('name', 'installments[' + i + '][amount]');
            inputAmount.setAttribute('id', 'installment_amount_' + i);
            inputAmount.classList.add('form-control');
            inputAmount.placeholder = 'Enter amount for installment ' + i;
            amountCol.appendChild(inputAmount);
            row.appendChild(amountCol);

            const dateCol = document.createElement('div');
            dateCol.classList.add('col-md-6', 'mb-3');
            const labelDate = document.createElement('label');
            labelDate.setAttribute('for', 'installment_date_' + i);
            labelDate.classList.add('form-label');
            labelDate.innerText = 'Payment Date';
            dateCol.appendChild(labelDate);
            const inputDate = document.createElement('input');
            inputDate.setAttribute('type', 'date');
            inputDate.setAttribute('name', 'installments[' + i + '][due_date]');
            inputDate.setAttribute('id', 'installment_date_' + i);
            inputDate.classList.add('form-control');
            dateCol.appendChild(inputDate);
            row.appendChild(dateCol);

            card.appendChild(row);
            container.appendChild(card);
        }
    }
}
</script>
        @include('admin.footer')
