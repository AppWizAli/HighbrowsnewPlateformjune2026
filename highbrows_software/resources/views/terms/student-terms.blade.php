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
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('studentcondition.create') }}">Add Rules</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Terms and Conditions</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Terms and Conditions</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Rules</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rules as $index => $rule)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                 
                                                    <td>{!! Str::limit($rule->rules, 50) !!}</td>

                                                    <td>
                                                     
                                                        <a href="{{ route('studentcondition.edit', $rule->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('studentcondition.destroy', $rule->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?')">
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
