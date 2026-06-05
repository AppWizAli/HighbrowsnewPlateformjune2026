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
                    <h4 class="card-title my-4">Update DateSheet</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> DateSheet</h4>
                    </div>
                    <div class="card-body bg-light">
           
                        <form class="mt-1" action="{{ route('exam.schedule.datesheet.update', ['id' => $exam->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="exam_schedule_id" value="{{ $exam->exam_schedule_id }}">
                            <div class="row">
                                <div class="col-md-3 px-5">
                                    <label class="form-control" for="subject">{{ $exam->subject->subj_name }}</label>
                                </div>
        
                                <!-- Date Selection -->
                                <div class="col-md-3 mb-3">
                                    <input type="date" class="form-control" name="date" value="{{ $exam->date ?? '' }}" required>
                                </div>
        
                                <!-- Start Time -->
                                <div class="col-md-3 mb-3">
                                    <input type="time" class="form-control" name="start_time" value="{{ $exam->start_time ?? '' }}" required>
                                </div>
        
                                <!-- End Time -->
                                <div class="col-md-3 mb-3">
                                    <input type="time" class="form-control" name="end_time" value="{{ $exam->end_time ?? '' }}" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn " style="background-color: #084298;color:white;">Update Exam Schedule</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>

@include('admin.footer')
