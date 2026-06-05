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

                  <a href="{{ route('employee_add_attendance') }}" class="btn " style="background-color: #084298;color:white;">Add new attendance</a>

            </div>
                <div class="card shadow-lg">
                    <div class="card-header  text-white " style="background-color: #084298">
                      <h4 class="card-title">Employees Attendance</h4>
                    </div>

                    <div class="table-responsive">
                      <div id="attendanceTable" class="mt-4">
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
                              $count = $employees->firstItem();
                            @endphp
                            @foreach($employees as $employee)
                              <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $employee->name }}</td>

                                <td>{{ $employee->email }}</td>
                                <td>
                                  <a href="{{ route('show_employee_attendace', ['id' => $employee->id]) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                  </a>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <!-- Pagination -->
                      <div class="mt-3">
                        {{ $employees->links('pagination::bootstrap-5') }}
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
    document.getElementById('apply_cadet_colleges').addEventListener('change', function () {
    const cadetCollegesList = document.getElementById('cadetCollegesList');
    cadetCollegesList.style.display = this.checked ? 'block' : 'none';
});

        </script>

        @include('admin.footer')
