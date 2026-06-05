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
                    <h4 class="card-title my-4">Add New Exam</h4>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Exam</h4>
                     
                    </div>
                    <div class="card-body bg-light">
                        <form class="mt-1" action="{{route('datesheet_store', ['id' => $exam->id]) }}" method="POST">
                            @csrf
                            @foreach($subjects as $subject)
                            <div class="row">
                                <div class="col-md-3 px-5">
                                    <label class="form-control" for="subject">{{ $subject->subject_name }}</label>
                                    <input type="hidden" class="form-control" name="subjects[{{ $loop->index }}][subject_id]" value="{{ $subject->id }}" required>
                                </div>
                        
                                <!-- Date Selection -->
                                <div class="col-md-3 mb-3">
                                    <input type="date" class="form-control" name="subjects[{{ $loop->index }}][date]" required>
                                </div>
                        
                                <!-- Start Time -->
                                <div class="col-md-3 mb-3">
                                    <input type="time" class="form-control" name="subjects[{{ $loop->index }}][start_time]" required>
                                </div>
                        
                                <!-- End Time -->
                                <div class="col-md-3 mb-3">
                                    <input type="time" class="form-control" name="subjects[{{ $loop->index }}][end_time]" required>
                                </div>
                            </div>
                            @endforeach
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Add Exam Schedule</button>
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
