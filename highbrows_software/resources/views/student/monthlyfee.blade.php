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

    <div id="layoutSidenav_content">
        <main>
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Monthly Fee</h4>
                            </div>

                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif

                            <div class="card-body">
                                <h4 class="card-title mb-4">Filter Fees</h4>

                                <!-- Filtering Form -->
                                <form method="GET" action="{{ route('studentmonthlyfee.index') }}" class="d-flex justify-content-end mb-3">
                                    <div class="me-2">
                                        <label for="month" class="form-label">Select Month:</label>
                                        <select name="month" id="month" class="form-select">
                                            <option value="">All</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ request('month') == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="me-2">
                                        <label for="year" class="form-label">Select Year:</label>
                                        <select name="year" id="year" class="form-select">
                                            <option value="">All</option>
                                            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="align-self-end">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>

                                <!-- Show Table Only When Data is Available -->
                                @if(request()->filled('month') || request()->filled('year') || $monthlyFees)
                                    @if($monthlyFees->count() > 0)
                                        <h4 class="card-title mb-4">Fee List</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped text-center" id="datatablesSimple">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Receipt No.</th>
                                                        <th>Total Amount</th>
                                                        <th>Month</th>
                                                        <th>Pay</th>
                                                        <th>status by Admin</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($monthlyFees as $monthlyFee)
                                                        <tr>
                                                            <td>{{ $monthlyFee->id }}</td>
                                                            <td> {{ optional($monthlyFee->student)->full_name ?? $monthlyFee->user->name ?? 'N/A' }}</td>
                                                            <td>{{ $monthlyFee->receipt_no }}</td>
                                                            <td>{{ number_format($monthlyFee->total_amount, 2) }}</td>
                                                            <td>{{ $monthlyFee->fee_month }}</td>

                                                            <td>


                                                                <!-- Single Receipt Upload Form for Monthly Fee -->
                                                                <form action="{{ route('monthlyfee.upload', $monthlyFee->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                                                    @csrf
                                                                    <div class="input-group">
                                                                        <input type="file" name="receipt" class="form-control" required>
                                                                        <button type="submit" class="btn btn-primary">Upload</button>
                                                                    </div>
                                                                </form>

                                                                <!-- Show Uploaded Receipt -->
                                                                @if($monthlyFee->recepit)
                                                                    <a href="{{ asset('storage/receipts/' . $monthlyFee->recepit) }}" target="_blank" class="btn btn-success btn-sm mt-2">
                                                                        View Receipt
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($monthlyFee->status == 'pending')
                                                                <!-- Pay Button -->
                                                                <span class="badge bg-danger">Unpaid</span>
                                                            @elseif($monthlyFee->status == 'paid')
                                                                <!-- Paid Status -->
                                                                <span class="badge bg-success">Paid</span>
                                                            @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('monthlyfee.show', $monthlyFee->id) }}"
                                                                    class="btn btn-info btn-sm text-white" title="Print Admission Record">
                                                                    <i class="fas fa-print"></i>
                                                                </a>


                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-3">
                                            No records found for the selected month and year.
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info mt-3">
                                        Please apply filters to search for fees.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.footer')
