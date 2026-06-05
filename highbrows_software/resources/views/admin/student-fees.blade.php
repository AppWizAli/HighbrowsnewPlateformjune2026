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
            <div class="container">
                <div class="d-flex justify-content-start">
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('fees.create') }}">Generate Fee Challan</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Generate Fee Challan</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Fee Records</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Total Fee</th>
                                                <th>Advance</th>
                                                <th>Status</th>
                                                <th>Installments</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($fees as $fee)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $fee->student->full_name ?? 'N/A' }}</td>
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
                                                                <p>
                                                                    Amount: {{ $install->amount }} | Due Date: {{ $install->due_date }} |
                                                                    @if($install->receipt)
                                                                        <!-- Display the receipt link if available -->
                                                                        <a href="{{ asset('storage/receipts/' . $install->receipt) }}" class="btn btn-info" target="_blank" title="view">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    @else
                                                                        <span class="text-warning">No receipt uploaded</span>
                                                                    @endif
                                                                    <a href="{{ route('challan.generate', $install->id) }}" class="btn btn-primary" title="Generate Challan">
                                                                        <i class="fa-solid fa-print"></i>
                                                                    </a>
                                                                    <a href="#" class="btn btn-primary"  title="Update Status"  id="status-btn-{{ $install->id }}"
                                                                    onclick="submitStatusAjax({{ $install->id }}); return false;" >
                                                                     <i class="fa-solid fa-close" id="status-icon-{{ $install->id }}"></i>
                                                                 </a>

                                                                </p>
                                                            @endforeach
                                                        @else
                                                            <p>No installments found.</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- <a href="{{ route('fees.edit', $fee->id) }}" class="btn btn-warning btn-sm"> <i class="fas fa-edit"></i></a> --}}
                                                        <a href="{{ route('receipt.generate', $fee->id) }}" class="btn btn-success" title="Generate Receipt">
                                                            <i class="fa-solid fa-receipt"></i>
                                                        </a>
                                                        <form action="{{ route('fees.destroy', $fee->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this fee record?')" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>


@include('admin.footer')
<script>
function submitStatusAjax(id) {
    const icon = document.getElementById('status-icon-' + id);
    const btn = document.getElementById('status-btn-' + id);

    if (!icon || !btn) {
        console.error('DOM elements not found');
        return;
    }

    // Show loading spinner
    icon.className = 'fa-solid fa-spinner fa-spin';
    icon.style.color = '';

    fetch(`/status/update/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to fetch');
        return response.json();
    })
    .then(data => {
        try {
            console.log('✅ Response:', data);

            if (data.status === 'Paid') {
                icon.className = 'fa-solid fa-check';
                icon.style.color = 'limegreen';
                btn.disabled = true;
            } else {
                icon.className = 'fa-solid fa-xmark';
                icon.style.color = 'orange';
            }

        } catch (err) {
            console.error('❌ Error inside .then():', err);
            icon.className = 'fa-solid fa-xmark';
            icon.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('🚨 Caught in .catch():', error);
        icon.className = 'fa-solid fa-xmark';
        icon.style.color = 'red';
    });
}
    </script>

