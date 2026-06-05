@include('admin.head')

<style>
    #layoutSidenav {
        display: flex;
        width: 100%;
    }

    #layoutSidenav .sidebar {
        width: 250px;
    }

    main {
        flex: 1;
        padding: 20px;
        background-color: #f8f9fa;
    }
</style>

@include('admin.nav')

<div id="layoutSidenav" class="d-flex">
    <!-- Sidebar Section -->
    <div class="sidebar">
        @if(auth()->user()->usertype == 'admin')
        @include('admin.sidebar') <!-- Include the Admin Sidebar -->
    @elseif(auth()->user()->usertype == 'subadmin')
        @include('subadmin.sidebar') <!-- Include the Subadmin Sidebar -->
    @else
    @include('student.sidebar')
    @endif
    </div>

    <!-- Main Content Section -->
    <main class="flex-grow-1">
        <div class="container-fluid px-4">
            <h3 class="mt-5">Welcome, {{ Auth::user()->name }}</h3>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>

            <!-- Fee Record Section -->
            @if($fees->isEmpty())
                <div class="alert alert-warning text-center">
                    No fee record available.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Total Fee</th>
                                <th>Advance</th>
                                <th>Status</th>
                                <th>Installments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fees as $fee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $fee->total_fee }}</td>
                                    <td>{{ $fee->advance }}</td>
                                    <td>
                                        <span class="badge
                                            @if($fee->status == 'Paid') bg-success
                                            @elseif($fee->status == 'Pending') bg-warning
                                            @elseif($fee->status == 'Overdue') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ $fee->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($fee->installment->isNotEmpty())
                                            @foreach($fee->installment as $install)
                                                <div class="mb-2 p-2 border rounded">
                                                    <p>Amount: {{ $install->amount }} | Due: {{ $install->due_date }}</p>

                                                    <!-- Upload Receipt Form -->
                                                    <form action="{{ route('receipt.upload', $install->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="input-group">
                                                            <input type="file" name="receipt" class="form-control" required>
                                                            <button type="submit" class="btn btn-primary">Upload</button>
                                                        </div>
                                                    </form>

                                                    <!-- Show Uploaded Receipt -->
                                                    @if($install->receipt)
                                                        <a href="{{ asset('storage/receipts/' . $install->receipt) }}" target="_blank" class="btn btn-success btn-sm mt-2">
                                                            View Receipt
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No Installments</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>

</div>

@include('admin.footer')
