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
            
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Student Attendance</h4>
                     
                    </div>
                    <div class="card-body bg-light">
                    <form action="{{ route('student_attendance_store') }}" method="POST">
                        @csrf
                        <div class="form-group ">
                            <label for="date">Date:</label>
                            <input type="date" name="date" id="date" class="form-control w-50" required>
                            <button type="button" id="toggleAttendance" class="btn  mt-3" style="color: white;background-color:#084298">Add Attendance</button>
                        
                        </div>
                        
                        <!-- Attendance Table -->
                        <div id="attendanceTable" class="mt-4" style="display: none;">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Roll_No</th>
                                        <th>Father Contact</th>
                                        <th>Attendance</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                  @php
                                    $count=0;
                                  @endphp
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $student->custom_id }}</td>
                                        <td>{{ $student->guardian_phone }}
                                          <input type="text" name="class" value={{ $student->grade_applied_for }} hidden>
                                        </td>
                                        
                                        
                                        <td>
                                             <input type="radio" name="attendance[{{ $student->id }}]" value="present" > Present 
                                        
                                           <input type="radio" name="attendance[{{ $student->id }}]" value="absent">  Absent
                                       
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="leave">  Leave
                                       

                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success mt-3">Submit Attendance</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>


        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.getElementById('date');
            var today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
        });
        document.getElementById('toggleAttendance').addEventListener('click', function() {
            var attendanceTable = document.getElementById('attendanceTable');
            if (attendanceTable.style.display === "none") {
                attendanceTable.style.display = "block";
            } else {
                attendanceTable.style.display = "none";
            }
        });
      </script>
        @include('admin.footer') 
