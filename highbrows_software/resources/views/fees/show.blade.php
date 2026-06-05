@include('admin.head')
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

    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="d-flex justify-content-start">
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('monthlyfee.create') }}">
                        Generate Challan
                    </a>
                    <a class="btn my-4 text-white p-2" style="background-color: #089850" href="{{ route('monthlyfee.generateForCurrentMonth') }}">
                        Generate Challan All
                    </a>
                </div>

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
                                <form method="GET" action="{{ route('monthlyfee.index') }}" class="d-flex justify-content-end mb-3">
                                    <div class="me-2">
                                        <label for="month" class="form-label">Select Grade:</label>
                                        <select name="grade" id="grade" class="form-select">
                                            <option value="">All</option>
                                            @for ($grade = 5; $grade <= 12; $grade++)
                                                <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>
                                                    {{ $grade }}
                                                </option>
                                            @endfor
                                            <option value="Issb">Issb</option>
                                        </select>
                                    </div>
                                    <div class="me-2">
                                        <label for="month" class="form-label">Select Category:</label>
                                        <select id="category" name="category" class="form-select">
                                            <option value="" >All</option>

                                            <option value="Online">Online</option>
                                            <option value="DayScholar">DayScholar</option>
                                            <option value="Hostel">Hostel</option>
                                        </select>
                                    </div>
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
                                @if(request()->filled('month') || request()->filled('year')||$monthlyFees)

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
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($monthlyFees as $monthlyFee)
                                                        <tr>
                                                            <td>{{ $monthlyFee->id }}</td>
                                                            <td>{{ optional($monthlyFee->student)->full_name ?? $monthlyFee->user->name ?? 'N/A' }}</td>
                                                            <td>{{ $monthlyFee->receipt_no }}</td>
                                                            <td>{{ number_format($monthlyFee->total_amount, 2) }}</td>
                                                            <td>{{ $monthlyFee->fee_month }}</td>
                                                            <td>
                                                                @if($currentUserType == 'admin')
                                                                @if($monthlyFee->status == 'pending')
                                                                <!-- Pay Button -->
                                                                <a href="javascript:void(0);"
   class="btn btn-success btn-sm"
   data-bs-toggle="modal"
   data-bs-target="#payFeeModal"
   data-fee-id="{{ $monthlyFee->id }}">
   Pay
</a>
                                                            @elseif($monthlyFee->status == 'paid')
                                                                <!-- Paid Status -->
                                                                <span class="badge bg-success">Paid</span>
                                                            @endif
                                                            @endif
                                                            @if($monthlyFee->receipt)
                                                            <a href="{{ Storage::disk('public')->url('receipts/' . $monthlyFee->receipt)  }}" target="_blank" class="btn btn-success btn-sm mt-2">
                                                                View Receipt
                                                            </a>
                                                            @else
                                                            <a href="{{ route('monthlyfee.show', $monthlyFee->id) }}"
                                                                class="btn btn-info btn-sm text-white" title="Print Admission Record">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        @endif

                                                                <a href="{{ route('monthlyfee.edit', $monthlyFee->id) }}"
                                                                    class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('monthlyfee.destroy', $monthlyFee->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Are you sure you want to delete this MonthlyFee?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-3">
                                            No records found for the selected grade,category, month and year.
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
            
            <!-- Modal -->
<!-- Pay Modal -->
<!-- Modal -->
<div class="modal fade" id="payFeeModal" tabindex="-1" aria-labelledby="payFeeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('monthlyfee.pay.submit') }}">
      @csrf
      <input type="hidden" name="monthly_fee_id" id="monthlyFeeId">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="payFeeModalLabel">Pay Monthly Fee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="receiving_date" class="form-label">Receiving Date</label>
            <input type="date" class="form-control" name="receiving_date" required>
          </div>
          <div class="mb-3">
            <label for="receiver_name" class="form-label">Receiver Name</label>
            <input type="text" class="form-control" name="receiver_name" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Mark as Paid</button>
        </div>
      </div>
    </form>
  </div>
</div>



        </main>
    </div>
</div>

@include('admin.footer')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var payFeeModal = document.getElementById('payFeeModal');
    payFeeModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var feeId = button.getAttribute('data-fee-id');
        document.getElementById('monthlyFeeId').value = feeId;
    });
});
</script>
