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
                <div class="card shadow-lg">
                    <div class="card-header  text-white " style="background-color: #084298">
                        <h2>Employee Attendace</h2>
                    </div>
                    <div class="card-body">
                        {{-- @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif --}}

<div class="form-container">
  <form action="{{ route('employee_attendance_store') }}" method="POST">
      @csrf
      <div class="form-group ">
          <label for="date">Date:</label>
          <input type="date" name="date" id="date" class="form-control w-50" required>
          <button type="button" id="toggleAttendance" class="btn  mt-3" style="background-color: #084298;color:white;">Add Attendance</button>

      </div>

      <!-- Attendance Table -->
      <div id="attendanceTable" class="mt-4" style="display: none;">
          <table class="table table-bordered text-center">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Employee</th>

                      <th>Email</th>
                      <th>Attendance</th>

                  </tr>
              </thead>
              <tbody>
                @php
                  $count=0;
                @endphp
                  @foreach($employees as $employee)
                  <tr>
                      <td>{{ ++$count }}</td>
                      <td>{{$employee->name  }}</td>

                      <td>{{ $employee->email }}</td>
                      <td>
                        <input type="radio" name="attendance[{{ $employee->id }}]" value="present" > Present

                         <input type="radio" name="attendance[{{ $employee->id }}]" value="absent">  Absent

                          <input type="radio" name="attendance[{{ $employee->id }}]" value="leave">  Leave

                        <input type="radio" name="attendance[{{ $employee->id }}]" value="late">   Late

                         <input type="radio" name="attendance[{{ $employee->id }}]" value="excused_late">  Excused Late
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
            </div>
        </main>
    </div>

</div>


        </div>
        <script>
    document.getElementById('apply_cadet_colleges').addEventListener('change', function () {
    const cadetCollegesList = document.getElementById('cadetCollegesList');
    cadetCollegesList.style.display = this.checked ? 'block' : 'none';
});

        </script>
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

