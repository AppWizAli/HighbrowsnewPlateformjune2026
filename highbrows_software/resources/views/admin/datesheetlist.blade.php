@include('admin.head')
<style>
  @media print {
    .btn{
      display: none;
    }
    .hide{
      display: none
    }
    .table {
      width: 100% !important;
    }
  }
</style>
@include('admin.nav')  
<div id="layoutSidenav">
  @if(auth()->user()->usertype == 'admin')
        @include('admin.sidebar') <!-- Include the Admin Sidebar -->
    @elseif(auth()->user()->usertype == 'subadmin')
        @include('subadmin.sidebar') <!-- Include the Subadmin Sidebar -->
    @else
    @include('student.sidebar') 
    @endif

    <!-- Container for the form, adjusted to the right of the sidebar -->
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="d-flex justify-content-start">
                    {{-- <h4 class="card-title my-4">Add New Subject</h4> --}}
                    <button class="btn  mb-3" onclick="window.print()" style="background-color: #084298;color:white;">
                      <i class="fas fa-print"></i> Print Datesheet
                  </button>
                  
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> DateSheet</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{session('message')}}</div>
                            @endif
                            <div class="card-body">
                                {{-- <h4 class="card-title mb-4">Subjects List</h4> --}}
                                <div class="table-responsive">
                               
                                    <table class="table table-striped table-bordered" id="examsTable">
                                        <thead>
                                          <tr>
                                            <th>Sr.no</th>
                                            <th>Subject</th>
                                            <th>Time</th>
                                            <th>Date</th>
                                            <th class="hide">Actions</th>
                                          </tr>
                                        </thead>
                    
                                        <tbody>
                                            @php
                                                $count = 0;
                                            @endphp
                                          @foreach ($datesheets as $exam)
                                          @if($exams->id == $exam->exam_schedule_id )
                                          <tr>
                                            <td>{{ ++$count }}</td>
                    
                                            <td>{{$exam->subject->subj_name}}</td>
                                            <td>{{ $exam->start_time }} to {{$exam->end_time}}</td>
                                            <td>{{ $exam->date }}</td>
                                            <td class="hide">
                                               <!-- Edit Button -->
                                               <a href="{{ route('exam-schedule-date-edit', ['id' => $exam->id ]) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                              </a>
                                              <form action="{{ route('exam-schedule-date_delete', ['id' => $exam->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                  <i class="fas fa-trash"></i>
                                                </button>
                                              </form>
                                            </td>
                                          </tr>
                                          @endif
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
