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
                    <form action="{{ route('salary.createForCurrentMonth') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn my-4 text-white p-2" style="background-color: #084298">
                            Add Salaries
                        </button>
                    </form>
                </div>
                
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Employee Salries</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Salaries List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Teacher Name</th>
                                                <th>Salary Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($salaries as $salary)
                                                <tr>
                                                    <td>{{ $salary->teacher->name }}</td>
                                                    <td>{{ $salary->teacher->salary }}</td>
                                                    <td>{{ $salary->status }}</td>
                                                    <td>
                                                        @if($salary->status == 'unpaid')
                                                            <!-- Pay Button -->
                                                            <a href="{{ route('salary.pay', $salary->id) }}" 
                                                                class="btn btn-success btn-sm" 
                                                                onclick="return confirm('Are you sure you want to mark this salary as paid?');">Pay</a>
                                                        @endif
                                    
                                                        <!-- Print Receipt Button -->
                                                        <a href="{{ route('salary.receipt', $salary->id) }}" 
                                                            class="btn btn-primary btn-sm" 
                                                            target="_blank">Print Receipt</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $salaries->links() }} 
                                    
                                
                                    
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
