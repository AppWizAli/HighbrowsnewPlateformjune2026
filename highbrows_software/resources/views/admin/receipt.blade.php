<!DOCTYPE html>
<html>
<head>
    <title>Advance Payment Receipt</title>
    <style>
        .receipt-container {
            width: 100%;
            padding: 20px;
            border: 1px solid #000;
        }
        .header, .footer {
            text-align: center;
        }
        .details-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .details-table th, .details-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h2>Receipt for Advance Payment</h2>
            <p>Student: {{ optional($fee->student)->full_name ?? optional($fee->student)->name ?? 'N/A' }}</p>
            <p>Advance Amount: {{ $fee->advance }}</p>
            <p>Payment Date: {{ $fee->created_at }}</p>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Advance Paid</td>
                    <td>{{ $fee->advance }}</td>
                </tr>
                <tr>
                    <td>Total Fee</td>
                    <td>{{ $fee->total_fee }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Receipt Number: {{ $fee->id }}</p>
            {{-- <p>Payment Method: {{ $fee->payment_method }}</p> --}}
            <p>Address: HighBrows Acadmey</p>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print(); 
        }
    </script>
</body>
</html>
