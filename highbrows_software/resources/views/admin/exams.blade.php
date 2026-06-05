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
                    {{-- <h4 class="card-title my-4">Add New Subject</h4> --}}
                    <a class="btn my-4 text-white p-2 " style="background-color: #084298" href="{{route('exams.create')}}">Add New Exam</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-book"></i> Exams</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{session('message')}}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Exams List</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatablesSimple">
                                        <thead class="">
                                          <tr>
                                            <th>ID</th>
                                            <th>Exam Name</th>
                                            <th>Note</th>
                                            <th>Actions</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($exams as $exam)
                                            <tr>
                                              <td>{{ $exam->id }}</td>
                                              <td>{{ $exam->name }}</td>
                                              <td>{{ $exam->note }}</td>
                                              <td>
                                                <!-- Edit Button -->
                                                <a href="{{ route('exams.edit',  $exam->id) }}" class="btn btn-warning btn-sm mx-1" title="Edit">
                                                  <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('exams.destroy',  $exam->id) }}" method="POST" style="display:inline;">
                                                  @csrf
                                                  @method('DELETE')
                                                  <button type="submit" class="btn btn-danger btn-sm mx-1" title="Delete">
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
    function confirmDelete(employeeId) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + employeeId).submit();
        }
      })
    }

 
  </script>