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
                @if(auth()->user()->usertype == 'admin' || auth()->user()->usertype == 'subadmin')
                <div class="d-flex justify-content-start">
                    {{-- <h4 class="card-title my-4">Add New Subject</h4> --}}
                    <a class="btn my-4 text-white p-2 " style="background-color: #084298" href="{{route('exams.create')}}">Add New Exam</a>
                </div>
                @endif
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
                                <form class="mt-1" action="{{ route('result-add') }}" method="GET">
                                    @csrf
                                
                                    <div class="row mt-3">
                                        <!-- Exam Name -->
                                        <div class="col-md-3 px-5">
                                            <label for="exam">Exam Name</label>
                                            <select class="form-control" name="exam" required>
                                                <option value="" disabled selected>Select Exam</option>
                                                @foreach ($exams as $exam)
                                                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                
                                                @endforeach
                                            </select>
                                        </div>
                                
                                        <!-- Class Name -->
                                        <div class="col-md-3 mb-3">
                                            <label  for="class">Class Name</label>
                                            <select class="form-control" name="class" required>
                                                <option value="" disabled selected>Select Class</option>
                                                @foreach ($classe as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                
                                            </select>
                                        </div>
                                
                                   
                                        <!-- Subject -->
                                        <div class="col-md-3 mb-3">
                                            <label  for="subject">Subject</label>
                                            <select class="form-control" name="subject" required>
                                                <option value="" disabled selected>Select Subject</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->subj_name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                <div class="text-center">
                                    <button type="submit" class="btn mt-3" style="background-color: #084298;color:white;"> Add Marks </button>
                                </div>
                                
                                
                                </form>
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