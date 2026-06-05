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
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('subadmin.create') }}">Register Teacher</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i>  Teachers</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Teachers List</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center" id="datatablesSimple">
                                        <thead >
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subadmins as $subadmin)
                                            <tr>
                                                <td>{{ $subadmin->id }}</td>
                                                <td>{{ $subadmin->name }}</td>
                                                <td>{{ $subadmin->contact }}</td>
                                                <td>{{ $subadmin->email }}</td>
                                                <td>
                                                    @if(optional($subadmin->teachers->first())->id) 
                                               
                                                    <a href="{{ route('teachers.show', $subadmin->teachers->first()->id) }}" 
                                                       class="btn btn-info btn-sm" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('teachers.create', ['id' => $subadmin->id]) }}" 
                                                       class="btn btn-success btn-sm" title="Add Admission">
                                                        <i class="fas fa-user-plus"></i>
                                                    </a>
                                                @endif
                                                
                                                     
                                                    <a href="{{ route('subadmin.edit', $subadmin->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('subadmin.destroy', $subadmin->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this subadmin?')">   <i class="fas fa-trash"></i></button>
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
