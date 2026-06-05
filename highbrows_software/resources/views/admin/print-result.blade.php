<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Student Results</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> <!-- Assuming you use Bootstrap -->
    <style>
    
        .d-none {
            display: none !important;
        }


        @media print {
            .d-none {
                display: block !important;
            }

            .d-print-block {
                display: block !important;
            }

            .result-card {
                width: 100%;
                margin: 0 auto;
                margin-top: 100px;
                position: relative;
                z-index: 2;
            }

            h3, h4, h5, h6 {
                text-align: center;
                margin: 0;
                padding: 5px;
            }

            .result-summary {
                text-align: left;
            }

            .page-break {
                page-break-after: always;
            }

            .table-container {
                width: 90%;
                margin: 0 auto;
                margin-top: 30px;
            }

            table {
                border: 2px solid black;
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid black;
                padding: 8px;
            }

            .thead-dark th {
                background-color: #343a40 !important;
                color: white !important;
            }

            .result-summary {
                margin-top: 15px;
                text-align: right;
            }

            .footer-section {
                margin-top: 30px;
                text-align: center;
            }

            .footer-section p {
                margin: 0;
                font-size: 14px;
                color: #666;
            }

            .footer-section .signature {
                margin-top: 30px;
                display: flex;
                justify-content: space-around;
            }

            .footer-section .signature div {
                text-align: center;
            }

            .footer-section .signature div p {
                margin-top: 50px;
                border-top: 1px solid #333;
                width: 200px;
                margin-left: auto;
                margin-right: auto;
                padding-top: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div id="printArea" class="d-none d-print-block main">
        @foreach($students as $student)
        @if($results->where('student_id', $student->id)->first())
            <div class="result-card">

                <h3>RESULT CARD</h3>
                <h4>Highbrows Acadmey</h4>
                <h4>Results for {{ $student->full_name }}</h4>
                <h5>Class: {{ $student->grade->name }}\</h5>

                <div class="table-container">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Total</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalmarks = 0;
                                $obt = 0;
                                $avg = 0;
                            @endphp

                            @foreach ($results->where('student_id', $student->id) as $res)
                                <tr>
                                    <td class="align-middle">{{ $res->subject->subj_name }}</td>
                                    <td class="align-middle text-center">{{ $res->obt_marks }}</td>
                                    <td class="align-middle text-center">{{ $res->total }}</td>
                                    <td class="align-middle text-center">
                                        <span class="badge
                                            @if($res->grade == 'A') bg-success
                                            @elseif($res->grade == 'B') bg-info
                                            @elseif($res->grade == 'C') bg-warning
                                            @else bg-danger @endif
                                        ">
                                            {{ $res->grade }}
                                        </span>
                                    </td>
                                </tr>
                                @php
                                    $totalmarks += $res->total;
                                    $obt += $res->obt_marks;
                                @endphp
                            @endforeach
                            @php
                                $avg = $totalmarks ? ($obt / $totalmarks) * 100 : 0;
                            @endphp
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="4" class="text-end">
                                    <div class="result-summary mt-3">
                                        <p class="mb-1"><strong>Total Marks:</strong> {{ $totalmarks }}</p>
                                        <p class="mb-1"><strong>Total Obtained Marks:</strong> {{ $obt }}</p>
                                        <p><strong>Percentage:</strong> {{ number_format($avg, 2) }}%</p>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="footer-section">
                    <div class="signature">
                        <div>
                            <p>Homeroom Teacher</p>
                        </div>
                        <div>
                            <p>Head of Campus</p>
                        </div>
                        <div>
                            <p>Student Guardian</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Page break after each student's result -->
            <div class="page-break"></div>
        @endif
        @endforeach
    </div>

    <script>
        window.onload = function() {
            window.print();
        };

        window.onafterprint = function() {
            window.location.href = "{{ route('schedule.list') }}"; // Redirect to the list page
        };
    </script>

</body>
</html>
