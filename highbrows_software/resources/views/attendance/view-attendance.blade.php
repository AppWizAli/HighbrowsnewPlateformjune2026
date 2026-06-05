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
                <div class="d-flex justify-content-between mb-4">
                    <div>                    <a href="{{ route('employee_add_attendance') }}" class="btn " style="background-color: #084298; color:white;">Add new attendance</a></div>
                    <div>                    <a href="{{ route('employee_attendence') }}" class="btn " style="background-color: #084298; color:white;">View Employees Attencdance</a></div>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Attendance</h4>
                    </div>
                    <div class="card-body bg-light">
                        <div class="card mt-4">
                            <div class="card-body">
                                <h4 class="card-title">Teachers Information</h4>
                                <div class="container mt-4">
                                    <h4>View Attendance</h4>
                                    <form id="filterForm" class="mb-4">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="date">Date</label>
                                                <input type="date" name="date" id="date" class="form-control">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <button type="button" id="filterButton" class="btn" style="background-color: #084298; color:white">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Attendance Table -->
                                <div id="attendanceTable" class="table-responsive" style="display: none;">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Teacher</th>
                                                <th>Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attendanceBody"></tbody>
                                    </table>
                                </div>

                                <!-- Attendance Summary -->
                                <div id="attendanceSummary" class="mt-4" style="display: none;">
                                    <h5>Attendance Summary</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item">Total Teachers: <span id="totalTeachers">0</span></li>
                                        <li class="list-group-item">Present: <span id="presentTeachers">0</span></li>
                                        <li class="list-group-item">Absent: <span id="absentTeachers">0</span></li>
                                        <li class="list-group-item">On Leave: <span id="onLeaveTeachers">0</span></li>
                                        <li class="list-group-item">Late: <span id="lateTeachers">0</span></li>
                                        <li class="list-group-item">Excused Late: <span id="excusedLateTeachers">0</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('filterButton').addEventListener('click', function () {
    const date = document.getElementById('date').value;

    if (!date) {
        alert('Please select a date.');
        return;
    }

    fetch(`/attendance/employee/filter?date=${date}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('attendanceBody');
        tbody.innerHTML = '';

        if (data.teachers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3">No Teachers Found.</td></tr>';
            document.getElementById('attendanceSummary').style.display = 'none';
            return;
        }

        data.teachers.forEach((teacher, index) => {
            const attendance = data.attendanceRecords[teacher.id]?.status || 'Absent';
            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${teacher.name}</td>
                    <td>${attendance}</td>
                </tr>
            `;
        });

        // Update Summary
        document.getElementById('totalTeachers').textContent = data.stats.total;
        document.getElementById('presentTeachers').textContent = data.stats.present;
        document.getElementById('absentTeachers').textContent = data.stats.absent;
        document.getElementById('onLeaveTeachers').textContent = data.stats.onLeave;
        document.getElementById('lateTeachers').textContent = data.stats.late;
        document.getElementById('excusedLateTeachers').textContent = data.stats.excused_late;

        document.getElementById('attendanceTable').style.display = 'block';
        document.getElementById('attendanceSummary').style.display = 'block';
    })
    .catch(error => console.error('Error:', error));
});
</script>

@include('admin.footer')
