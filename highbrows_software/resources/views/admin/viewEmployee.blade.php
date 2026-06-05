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
                    {{-- <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('teachers.create') }}">Add New Admission</a> --}}
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
        
                        
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Teachers Detail</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <div class="text-center mb-4"><img src="{{ Storage::disk('public')->url($employee->image)}}" alt="Employee Image" class="img-fluid employee-image mt-5 " style="max-width: 150px;">
                                    <div class="employee-name">{{ $employee->name }}</div></div>
                                {{-- <h4 class="card-title mb-4">Teachers List</h4> --}}
                                <div class="table-responsive">
                                  <table class="table table-bordered">
                                    <tr>
                                        <th>Date of Birth</th>
                                        <td>{{ $employee->date_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td>{{ $employee->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $employee->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $employee->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $employee->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Joining Date</th>
                                        <td>{{ $employee->joining_date }}</td>
                                    </tr>
                                
                                    <!-- Display Assigned Classes -->
                                    <tr>
                                        <th>Assigned Classes</th>
                                        <td>
                                            @if($employee->classes->isEmpty())
                                                <span class="text-muted">No classes assigned</span>
                                            @else
                                                <ul>
                                                    @foreach($employee->classes as $class)
                                                        <li>{{ $class->name }} </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
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
