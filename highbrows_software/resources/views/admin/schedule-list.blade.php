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
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('schedule.create') }}">Add New Schedule</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i>Exams Schedule List</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Schedule List</h4>
                                <div class="table-responsive">
                                    <table class="table  table-bordered text-center" id="datatablesSimple">
                                        <thead>
                                          <tr>
                                            <th>Sr.no</th>
                                            <th>Exam Name</th>
                                            <th>Class</th>
                                      
                                            <th>Date</th>
                                            <th>More</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 0;
                                            @endphp
                                          @foreach ($examschedules as $exam)
                                          <tr>
                                            <td>{{ ++$count }}</td>
                    
                                            <td>
                                                {{ $exam->exam->name }}
                                            </td>
                                            <td>
                                               {{$exam->class->name}}
                    
                                            </td>
                                         
                                            <td>{{ $exam->start_date }} to {{$exam->end_date}}</td>
                    
                                            <td>
                                                <!-- Dropdown -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i> <!-- More options icon -->
                                                        {{-- <span class="sr-only">Toggle Dropdown</span> --}}
                                                    </button>
                                                    <ul class="dropdown-menu" style=" inset: auto !important; right: 0 !important;top: 20px !important;">
                                                        <!-- Add Button -->
                                                 
                                                        <li>
                                                            <a href="{{ route('date-sheet',['id'=>$exam->id]) }}" class="dropdown-item text-primary" title="Add">
                                                                <i class="fas fa-plus"></i> Add DateSheet
                                                            </a>
                                                        </li>
                                                        <!-- View Button -->
                                                        <li>
                                                            <a href="{{ route('date-sheet-list',['id' => $exam->id]) }}" class="dropdown-item text-info" title="View">
                                                                <i class="fas fa-eye"></i> View DateSheet
                                                            </a>
                                                        </li>
                                                        <li>

                                                            <a class="dropdown-item text-primary" id="printResultBtn" title="Print" href="{{route('exam-result',['id'=>$exam->id])}}"> <i class="fas fa-print"></i> Print Result</a>
                    
                    
                                                        </li>
                                                        <!-- Edit Button -->
                                                        <li>
                                                            <a href="{{ route('schedule.edit', ['id' => $exam->id]) }}" class="dropdown-item text-warning" title="Edit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        </li>
                                                        <!-- Delete Button -->
                                                        <li>
                                                            <form action="{{ route('schedule.destroy', ['id' => $exam->id]) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" title="Delete">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
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
