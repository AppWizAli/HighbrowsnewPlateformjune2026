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
                    <h4 class="card-title my-4">Add New College</h4>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Fees</h4>

                    </div>
                    <div class="card-body bg-light">


                        <form action="{{ route('fees.store') }}" method="POST" class="p-4 shadow rounded bg-white">
                            @csrf
                            <h4 class="mb-4 text-center" style="color: #084298;">Add Fee Details</h4>

                            <!-- Student Selection -->
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student</label>
                                <select name="student_id" id="student_id" class="form-select" required>
                                    <option value="" disabled selected>Select a Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Total Fee and Advance -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="total_fee" class="form-label">Total Fee</label>
                                    <input type="number" name="total_fee" class="form-control" placeholder="Enter total fee" value="{{ old('total_fee') }}" required>
                                    @error('total_fee')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="advance" class="form-label">Advance</label>
                                    <input type="number" name="advance" class="form-control" placeholder="Enter advance amount" value="{{ old('advance') }}">
                                    @error('advance')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Number of Installments -->
                            <div class="mb-3">
                                <label for="installments" class="form-label">No. of Installments</label>
                                <input type="text" name="installments" class="form-control" id="installments" placeholder="Enter installments number" oninput="createInstallmentFields()" value="{{ old('installments') }}" required>
                                @error('installments')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dynamic Installment Inputs -->
                            <div id="installment-container"></div>



                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100" style="background-color: #084298; color: white;">Submit</button>
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
