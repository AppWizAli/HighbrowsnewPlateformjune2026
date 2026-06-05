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
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('students.create') }}">Register Student<a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Registered Students</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Students List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="admissionsTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                                                                    <tr>
                                                                                        <td>{{ $loop->iteration }}</td>
                                                                                   
                                                                                        <td>{{ $student->name }}</td>
                                                                                        <td>{{ $student->email }}</td>
                                                                                        <td>{{ $student->contact }}</td>
                                                                                        <td>
                                                                                            {{-- <a href="{{ route('admissions.edit', $admission->id) }}"
                                                                                                class="btn btn-warning btn-sm" title="Edit">
                                                                                                <i class="fas fa-edit"></i>
                                                                                            </a> --}}
                                                                                            @if(optional($student->admissions->first())->id) 
                                                                                            {{-- If admission exists, show View button --}}
                                                                                            <a href="{{ route('admissions.show', $student->admissions->first()->id) }}" 
                                                                                               class="btn btn-info btn-sm" title="View">
                                                                                                <i class="fas fa-eye"></i>
                                                                                            </a>
                                                                                        @else
                                                                                            {{-- If no admission exists, show Add button --}}
                                                                                            <a href="{{ route('addstudent', ['user_id' => $student->id]) }}" 
                                                                                               class="btn btn-success btn-sm" title="Add Admission">
                                                                                                <i class="fas fa-user-plus"></i>
                                                                                            </a>
                                                                                        @endif
                                                                                        
                                                                                        
                                                                                             
                                                                                             
                                                                                            {{-- <a href="{{ route('admissions.print', $admission->id) }}"
                                                                                                class="btn btn-info btn-sm text-white" title="Print Admission Record">
                                                                                                <i class="fas fa-print"></i>
                                                                                             </a> --}}
                                                                                             
                                                                                            <form action="{{ route('students.destroy', $student->id) }}"
                                                                                                method="POST" style="display:inline;">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                                                    title="Delete" onclick="return confirm('Are you sure?')">
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
