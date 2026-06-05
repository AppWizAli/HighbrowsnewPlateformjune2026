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
                <div class="d-flex justify-content-start mb-4">
                    <a href="{{ route('attendance') }}" class="btn " style="background-color: #084298;color:white;">Add new attendance</a>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Subject</h4>
                     
                    </div>
                    <div class="card-body bg-light">
           
                                  <!-- Search Form -->
   
  
              <!-- Time Table -->
              <!-- Table for Class, Section, and Students -->
              <div class="card mt-4">
                <div class="card-body">
                  <h4 class="card-title">Class and Student Information</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered text-center">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Class</th>
           
                          <th>Students</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                          $serialNumber = 1;
                        @endphp
                        @foreach ($classes as $class)
                        <tr>
                          <td>{{ $serialNumber++ }}</td>
                          <td>{{ $class->name }}</td> 
           
                          <td>
                            <a href="javascript:void(0);" class="toggle-details" data-target="#details-{{ $class->id }}">
                              <i class="fas fa-chevron-down"></i>
                            </a>
                          </td>
                        </tr>
                        <tr id="details-{{ $class->id }}" class="details-row" style="display: none;">
                          <td colspan="4">
                            <div class="details-content">
                              @php
                                $count = 0;
                              @endphp
                              
                              <div class="card">
                                <div class="card-body">
                                  <h4 class="card-title">Student Attendance</h4>
                                  <div class="table-responsive">
                                    <div id="attendanceTable" class="mt-4">
                                      <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Roll_No</th>
                                            <th>Email</th>
                                            <th>Attendance</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 0; @endphp
                                            @foreach ($students as $student)
                                                @if ((int)$class->id === (int)$student->grade_applied_for)
                                                    <tr>
                                                        <td>{{ ++$count }}</td>
                                                        <td>{{ $student->full_name }}</td>
                                                        <td>{{ $student->custom_id }}</td>
                                                        <td>{{ $student->guardian_phone }}</td>
                                                        <td>
                                                            <a href="{{ route('show_student_attendance', ['id' => $student->id]) }}" class="btn btn-info btn-sm" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                          
                                            @if ($count == 0)
                                                <tr>
                                                    <td colspan="5">
                                                        There are no students for class {{ $class->name }}.
                                                    </td>
                                                </tr>
                                            @endif
                                          </tbody>
                                          
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              
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


        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
              const toggleLinks = document.querySelectorAll('.toggle-details');
          
              toggleLinks.forEach(link => {
                link.addEventListener('click', function () {
                  const target = document.querySelector(this.dataset.target);
                  if (target.style.display === "none") {
                    target.style.display = "table-row";
                  } else {
                    target.style.display = "none";
                  }
                });
              });
            });
          </script>
        @include('admin.footer') 
