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
            <div class="container mt-5">
                <div class="d-flex justify-content-start">
                    <h4 class="card-title my-4">Select Class For Marking Attandance</h4>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-clock"></i> Students Attandance</h4>
                     
                    </div>
                    <div class="card-body bg-light">
           
                        <form id="searchForm" method="GET" action="{{ route('add_student_attendance') }}" class="mb-4 mx-3">
                         
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class">Class</label>
                                        <select name="class" id="class" class="form-control">
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}" {{ $class->id == request()->query('class') ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="mt-3">
                                <button type="submit" class="btn px-4 text-white" style="background-color: #084298">Add Attendance</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>


        </div>
        @include('admin.footer') 
