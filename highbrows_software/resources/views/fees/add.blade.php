@include('admin.head')
<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@include('admin.nav')
@php($currentUserType = optional(auth()->user())->usertype)
<div id="layoutSidenav">
    @if($currentUserType == 'admin')
    @include('admin.sidebar')
@elseif($currentUserType == 'subadmin')
    @include('subadmin.sidebar')
    @elseif($currentUserType == 'cordinator')
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
    <form action="{{ route('monthlyfee.store') }}" method="POST">
        @csrf  {{-- CSRF Protection --}}

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="student_id" class="form-label">Name or Roll no.</label>
                <select name="student_id" id="student_id" class="form-select students" required>
                    <option value="" disabled selected>Select a Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->user_id }}"  data-admitted="true" {{ old('student_id') == $student->user_id ? 'selected' : '' }}>
                            {{ $student->full_name }} - {{ $student->custom_id }}
                        </option>
                    @endforeach
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"  data-admitted="false"  data-grade="{{ $user->grade }}"  {{ old('student_id') == $user->id ? 'selected' : '' }}>
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
                <label class="form-label">Father's Name:</label>
                <input type="text" id="father_name" class="form-control" name="father_name" value="{{ old('father_name') }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Class:</label>

                <input type="text" id="class" class="form-control" name="class" value="{{ old('class') }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Due Date:</label>
                <input type="date" class="form-control" name="date" value="{{ old('date') }}" required>
                @error('date')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Receiving Date:</label>
                <input type="date" class="form-control mb-3" name="receiving_date" value="{{ old('receiving_date') }}" >
                @error('receiving_date')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Fee For the Month of:</label>
                <input type="month" class="form-control" name="fee_month" value="{{ old('fee_month') }}" required>
                @error('fee_month')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
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
                    @php
                    $fees = [
                        'admission_fee' => 'Admission Fee (Non Refundable)',
                        'tuition_fee' => 'Tuition Fee',
                        'security_fee' => 'Security Fee',
                        'trip' => 'Trip',
                        'exam_fund' => 'Exam/Stationary/Sports fund/Com lab',
                        'school_functions' => 'School Functions',
                        'transport' => 'Transport',
                        'college_fee' => 'Colleges Fee',
                        'pocket_money' => 'Pocket Money',
                        'uniform_kit' => 'Uniform / Kit',
                        'fine' => 'Fine',
                        'late_fee_fine'=>'Late Fee Fine /Day',
                        'balance' => 'Balance',
                    ];
                    $sr = 1;
                    @endphp

                    @foreach ($fees as $key => $value)
                    <tr>
                        <td>{{ $sr++ }}</td>
                        <td>
                            <input type="text" class="form-control" name="fees[{{ $key }}][description]" value="{{ old('fees.' . $key . '.description', $value) }}" readonly required>
                            @error("fees.$key.description")
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control amount @if($key === 'tuition_fee') tuition-fee @endif @if($key === 'late_fee_fine') late_fee_fine @endif"
                                   name="fees[{{ $key }}][amount]"
                                   value="{{ old('fees.' . $key . '.amount', 0) }}" required>
                            @error("fees.$key.amount")
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Discount (%):</label>
                <input type="number" id="discount" class="form-control" name="discount" value="{{ old('discount', 0) }}" min="0" max="100">
                @error('discount')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Discounted Tuition Fee:</label>
                <input type="number" id="discountedTuitionFee" class="form-control" name="discounted_tuition_fee" value="{{ old('discounted_tuition_fee') }}" readonly>
                @error('discounted_tuition_fee')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Total Amount:</label>
                <input type="number" id="totalAmount" class="form-control" name="total_amount" value="{{ old('total_amount') }}" readonly>
                @error('total_amount')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Rs. (In Words):</label>
                <input type="text" class="form-control" name="amount_words" id="amount_words" readonly  required>
                @error('amount_words')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
    {{-- Receiver Name --}}
    <div class="col-md-6">
        <label class="form-label">Receiver Name:</label>
        <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name') }}" required>
        @error('receiver_name')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    {{-- Generated Name --}}
    <div class="col-md-6">
        <label class="form-label">Generated By:</label>
        <input type="text" class="form-control" name="generated_by" id="generated_by" value="{{ auth()->user()->name }}" readonly>
    </div>
</div>


        <button type="submit" class="btn text-white" style="background-color: #084298">Submit</button>
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

<!--<script>-->

<!--document.addEventListener('DOMContentLoaded', function () {-->

<!--    function calculateTotal() {-->
<!--        let total = 0;-->
<!--        let tuitionFee = 0;-->
<!--        let discount = Number(document.getElementById('discount').value) || 0;-->
<!--        let words='';-->
        // Loop through all .amount fields to sum them up
<!--        document.querySelectorAll('.amount').forEach(input => {-->
<!--            if (input.classList.contains('late_fee_fine')) {-->
<!--        return;-->
<!--    }-->
<!--            let amount = Number(input.value) || 0;-->
<!--            if (input.classList.contains('tuition-fee')) {-->
<!--                tuitionFee = amount;-->
<!--            } else {-->
<!--                total += amount;-->
<!--            }-->
<!--        });-->

        // Calculate the discounted tuition fee
<!--        let discountAmount = (tuitionFee * discount) / 100;-->
<!--        let discountedTuition = tuitionFee - discountAmount;-->

        // Set the discounted tuition and total amount in the fields
<!--        document.getElementById('discountedTuitionFee').value = discountedTuition.toFixed(2);-->
<!--        document.getElementById('totalAmount').value = (total + discountedTuition).toFixed(2);-->

        // Get the total amount value
<!--        const totalAmount = parseFloat(document.getElementById('totalAmount').value) || 0;-->

        // Send the total amount to the backend for conversion to words
<!--        fetch('/highbrows_software/convert-number-to-words', {-->
<!--            method: 'POST',-->
<!--            headers: {-->
<!--                'Content-Type': 'application/json',-->
<!--                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')-->
<!--            },-->
<!--            body: JSON.stringify({ amount: totalAmount })-->
<!--        })-->
<!--        .then(response => response.json())-->
<!--        .then(data => {-->
<!--            words=data.words-->
            // console.log(words);
            // Optionally show in HTML
<!--            let amountWordsElement = document.getElementById('amount_words');-->
<!--            if (amountWordsElement) {-->
                // Assign the words to the input element
<!--                amountWordsElement.value = words;-->
                // console.log(words); // For input fields
<!--            } else {-->
<!--                console.error('Element with id "amount_words" not found!');-->
<!--            }-->

<!--        })-->
<!--        .catch(error => console.error('Error converting to words:', error));-->

<!--    }-->

    // Add event listeners to dynamically detect changes
<!--    document.querySelectorAll('.amount, #discount').forEach(input => {-->
<!--        input.addEventListener('input', calculateTotal);-->
<!--    });-->

    // Run the function initially to set values
<!--    calculateTotal();-->
<!--});-->

<!--</script>-->
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
