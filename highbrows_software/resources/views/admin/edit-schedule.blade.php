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
                    <h4 class="card-title my-4">Edit Schedule</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Exam Schedule</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form class="mt-4" action="{{route('schedule.update',['id'=>$schedule->id])}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                <select class="form-control" id="class_id" name="class_id" required>
                                    <option value="">Select Class</option>
                                    <!-- Options populated dynamically -->
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}"{{$class->id == $schedule->class_id ? 'selected' : ''}} >{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="mb-3">
                                <label for="exam_id" class="form-label">Exam <span class="text-danger">*</span></label>
                                <select class="form-control" id="exam_id" name="exam_id" required>
                                    <option value="">Select Exam</option>
                                    <!-- Options populated dynamically -->
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}"{{$exam->id == $schedule->exam_id ? 'selected' : ''}}>{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_time" name="start_date"  value={{$schedule->start_date}} required>
                            </div>
        
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_time" name="end_date" value={{$schedule->end_date}} required>
                            </div>
        
                            <button type="submit" class="btn " style="background-color: #084298;color:white">update Exam Schedule</button>
                        </form>
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.footer')
