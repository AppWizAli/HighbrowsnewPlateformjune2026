


@include('admin.head')
@include('admin.nav')
<div id="layoutSidenav">
    @include(auth()->user()->usertype === 'admin' ? 'admin.sidebar' : (auth()->user()->usertype === 'subadmin' ? 'subadmin.sidebar' : (auth()->user()->usertype === 'cordinator' ? 'cordinator.sidebar' : 'student.sidebar')))
<style>
    .blue {
    background-color: #007bff;
    color: white;
}
</style>
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <h4>Expenses List</h4>

                <form method="GET" action="{{ route('expenses.index') }}" class="row mb-3">
                    <div class="col-md-3">
                        <label for="month" class="form-label">Month</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">Select Month</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="year" class="form-label">Year</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">Select Year</option>
                            @foreach(range(now()->year, 2020) as $y)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                        <a href="{{ route('expenses.create') }}" class="btn btn-success">Add Expense</a>
                    </div>
                </form>

                <div class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead class="blue">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Total (Rs)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $index => $expense)


                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-M-Y') }}</td>

                            <!-- Combine all descriptions into one column -->
                            <td>
                                @foreach($expense->details as $desc)
                                    {{ $desc['description'] }}<br>
                                @endforeach
                            </td>

                            <!-- Calculate the total sum of amounts -->
                            <td>{{ number_format(collect($expense->details)->sum('amount'), 2) }}</td>

                            <!-- Actions (Edit and Delete) -->
                            <td class="align-middle text-center">
    {{-- Upload Receipt Icon --}}
    @if ($expense->image)
    {{-- Show View Icon --}}
    <a href="{{ Storage::disk('public')->url($expense->image); }}" target="_blank" class="d-block mb-2" title="View Receipt">
        <i class="fas fa-eye text-success"></i>
    </a>
@else
    {{-- Show Upload Icon --}}
    <form action="{{ route('expense.upload', $expense->id) }}" method="POST" enctype="multipart/form-data" style="display: inline;">
        @csrf
        <label for="upload-{{ $expense->id }}" style="cursor: pointer;">
            <i class="fas fa-upload text-primary mb-2" title="Upload Receipt"></i>
        </label>
        <input id="upload-{{ $expense->id }}" type="file" name="image" style="display: none;" onchange="this.form.submit()">
    </form>
@endif

    {{-- Edit Button --}}
    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">Edit</a>

    {{-- Delete Button --}}
    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger">Delete</button>
    </form>
</td>

                        </tr>
                      

                    @empty
                        <tr><td colspan="5" class="text-center">No records found</td></tr>
                    @endforelse

                    </tbody>
                </table>
                </div>
            </div>
        </main>
    </div>
</div>
@include('admin.footer')

