@include('admin.head')
<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                <div class="card shadow-lg">
                    <div class="card-header  text-white " style="background-color: #084298">
                        <h4>Monthly Fees</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif



<div class="container mt-4">
    <h2 class="text-center">Fee Receipt Form</h2>
    <form action="{{ route('monthlyfee.update', $monthlyFee->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Use PUT method for updates --}}

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="student_id" class="form-label">Name or Roll no.</label>
                <select name="student_id" id="student_id" class="form-select students" required>
                    <option value="" disabled selected>Select a Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->user_id }}"  data-admitted="true" {{ old('student_id',$monthlyFee->student_id) == $student->user_id ? 'selected' : '' }}>
                            {{ $student->full_name }} - {{ $student->custom_id }}
                        </option>
                    @endforeach
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"  data-admitted="false"  data-grade="{{ $user->grade }}"  {{ old('student_id',$monthlyFee->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{'with no admission' }}
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Date:</label>
                <input type="date" class="form-control" name="date" value="{{ old('date', $monthlyFee->date) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Receiving Date:</label>
                <input type="date" class="form-control" name="receiving_date" value="{{ old('receiving_date', $monthlyFee->receiving_date) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Fee For the Month of:</label>
                <input type="month" class="form-control" name="fee_month" value="{{ old('fee_month', $monthlyFee->fee_month) }}" required>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>SR.NO</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sr = 1; @endphp
                    @foreach ($monthlyFee->details as $fee)
                    <tr>
                        <td>{{ $sr++ }}</td>
                        <td>
                            <input type="text" class="form-control" name="fees[{{ $loop->index }}][description]" 
                                   value="{{ old("fees.$loop->index.description", $fee->description) }}" required>
                        </td>
                        <td>
                            <input type="number" class="form-control amount" 
                                   name="fees[{{ $loop->index }}][amount]" 
                                   value="{{ old("fees.$loop->index.amount", $fee->amount) }}" required>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

     <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Discount (%):</label>
                <input type="number" id="discount" class="form-control" name="discount" value="{{ old('discount', $monthlyFee->discount) }}" min="0" max="100">
                @error('discount')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <!--<div class="col-md-6">-->
            <!--    <label class="form-label">Discounted Tuition Fee:</label>-->
            <!--    <input type="number" id="discountedTuitionFee" class="form-control" name="discounted_tuition_fee" value="{{ old('discounted_tuition_fee', $monthlyFee->discounted_tuition_fee) }}" readonly>-->
            <!--    @error('discounted_tuition_fee')-->
            <!--        <div class="text-danger mt-2">{{ $message }}</div>-->
            <!--    @enderror-->
            <!--</div>-->
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Total Amount:</label>
                <input type="number" id="totalAmount" class="form-control" name="total_amount" value="{{ old('total_amount', $monthlyFee->total_amount) }}" readonly>
                @error('total_amount')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Rs. (In Words):</label>
                <input type="text" class="form-control" name="amount_words" id="amount_words" value="{{ old('amount_words', $monthlyFee->amount_words) }}" readonly  required>
                @error('amount_words')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            
            {{-- Receiver Name --}}
    <div class="col-md-6">
        <label class="form-label">Receiver Name:</label>
        <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name', $monthlyFee->receiver_name) }}" required>
        @error('receiver_name')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    {{-- Generated Name --}}
    <div class="col-md-6">
        <label class="form-label">Generated By:</label>
        <input type="text" class="form-control" name="generated_by" id="generated_by" value="{{ old('receiver_name', $monthlyFee->generated_by) }}" readonly>
    </div>
        </div>


      

 

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    
</div>




                    </div>
                </div>
            </div>
        </main>
    </div>
    
</div>


        </div>
   <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
        <script>
 CKEDITOR.replace('description');
        </script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

  function calculateTotal() {
    let total = 0;
    let discount = Number(document.getElementById('discount').value) || 0;

    // Sum all amounts except late_fee_fine
    document.querySelectorAll('.amount').forEach(input => {
        if (input.classList.contains('late_fee_fine')) return;
        let amount = Number(input.value) || 0;
        total += amount;
    });

    // Apply fixed discount to total
    let finalAmount = Math.max(total - discount, 0); // prevent negative total

    // Update total field
    document.getElementById('totalAmount').value = finalAmount.toFixed(2);

    // Convert to words via backend
    fetch('/highbrows_software/convert-number-to-words', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ amount: finalAmount })
    })
    .then(response => response.json())
    .then(data => {
        const amountWordsElement = document.getElementById('amount_words');
        if (amountWordsElement) {
            amountWordsElement.value = data.words;
        } else {
            console.error('Element with id "amount_words" not found!');
        }
    })
    .catch(error => console.error('Error converting to words:', error));
}

// Attach event listeners
document.querySelectorAll('.amount, #discount').forEach(input => {
    input.addEventListener('input', calculateTotal);
});

// Run initially
calculateTotal();

});

</script>

<script>
//     $(document).ready(function() {
//     $('.students').select2();
// });
$(document).ready(function () {
    $('.students').select2();

    $('#student_id').change(function () {
        let studentId = $(this).val();
        let selectedOption = $(this).find('option:selected');
        let isAdmitted = selectedOption.data('admitted');

        if (studentId && isAdmitted) {
            $.ajax({
                url: '/highbrows_software/get-student-details/' + studentId,
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    
                    if (response) {
                        $('#father_name').val(response.father_name);
                        $('#class').val(response.class);
                    } else {
                        $('#father_name').val('');
                        $('#class').val('');
                    }
                },
                error: function () {
                    $('#father_name').val('');
                    $('#class').val('');
                }
            });
        } else {
            $('#father_name').val('');
            $('#class').val(selectedOption.data('grade') || '');
        }
    });
});


</script>
</div>



        @include('admin.footer') 
