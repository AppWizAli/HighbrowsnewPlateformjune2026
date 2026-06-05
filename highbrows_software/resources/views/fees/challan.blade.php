<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fee Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .receipt-container {
            width: 600px;
            margin: auto;
            border: 2px solid #084298;
            padding: 15px;
        }
        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .sub-header {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
        }
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .fee-table, .fee-table th, .fee-table td {
            border: 1px solid black;
            text-align: left;
            padding: 5px;
        }
        .footer {
            margin-top: 15px;
        }
        .signature {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="header">HIGHBROWS PRE CADET SCHOOL</div>
    <div class="sub-header">
        <b>Main Campus:</b> Sawan, GT Road, Rawalpindi - 0307-5973563 / 051-5921778 <br>
        <b>Sub Campus:</b> Old Lalazar, Rawalpindi - 051-5122055
    </div>

    <table class="info-table">
        <tr>
            <td><b>Due Date:</b> {{ $monthlyFee->date }}</td>
          <td></td>
          <td><b>Receipt No:</b> {{ $monthlyFee->receipt_no }}</td>
        </tr>
        @if($monthlyFee->student)
        <tr>
            <td><b>Name:</b> {{ $monthlyFee->student->full_name }}</td>
            <td><b>Roll No. :</b> {{ $monthlyFee->student->custom_id }}</td>
            <td><b>Father's Name:</b> {{ $monthlyFee->student->father_name }}</td>
        </tr>
        @endif
        <tr>
            @if($monthlyFee->student) <td><b>Class:</b> {{ $monthlyFee->student->grade_applied_for }}</td>
            @endif


            <td><b>Fee Month:</b> {{ $monthlyFee->fee_month }}</td>
        </tr>
        <tr>
            <td><b>Receiving Date:</b> {{ $monthlyFee->receiving_date }}</td>
        </tr>
    </table>

    <table class="fee-table">
        <thead>
            <tr>
                <th>SR.NO</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($monthlyFee->details as $index => $fee)
            @if ($fee->description !== "Late Fee Fine /Day")
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $fee->description }}</td>
                    <td>{{ number_format($fee->amount, 2) }}</td>
                </tr>
            @elseif ($fee->description === "Late Fee Fine /Day" && $fee->amount > 0)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $fee->description }}</td>
                    <td id="l_fine"></td>
                </tr>
                <input type="hidden" id="latefee" value="{{ number_format($fee->amount, 2) }}">
                <input type="hidden" id="duedate" value="{{ $monthlyFee->date }}">
            @endif
        @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="d-flex align-items-center gap-2">
            <b>Total:</b>
            <span id="total">{{ number_format($monthlyFee->total_amount, 2) }}</span>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2">
            <b>Rs. (In Words):</b>
            <span id="t_words">{{ $monthlyFee->amount_words }}</span>
        </div>
    </div>


    <div class="signature">
        @if($monthlyFee->status=='paid')
        <img src="{{ asset('storage/receipts/imgpaid.png') }}" alt="Signature" style="width: 150px; height: auto; margin-top: 10px;"> <br>
              @endif
        <b>Receiver Name:</b> {{ $monthlyFee->receiver_name }} <br>
        <b>Signature:</b> ______________________
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const lateFeePerDay = parseFloat($('#latefee').val()) || 0;
    const dueDateStr = $('#duedate').val();

    if (lateFeePerDay > 0 && dueDateStr) {
        const dueDate = new Date(dueDateStr);
        const currentDate = new Date();

        const timeDiff = currentDate - dueDate;
        const dayDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

        if (dayDiff > 0) {
            const totalLateFee = lateFeePerDay * dayDiff;
            const currentTotal = parseFloat(@json($monthlyFee->total_amount)) + totalLateFee;

            $('#l_fine').text('');
            $('#l_fine').text(totalLateFee.toFixed(2));
            $('#total').text('');
            $('#total').text( currentTotal.toFixed(2));

            // AJAX call to Laravel for number-to-words conversion
            $.ajax({
                url: '/convert-number-to-words',
                method: 'POST',
                data: {
                    amount: currentTotal,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#t_words').text('');
                    $('#t_words').text( response.words);
                },
                error: function () {
                    console.error("Error converting number to words");
                }
            });
        }
    }
});


</script>

<script>
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>
