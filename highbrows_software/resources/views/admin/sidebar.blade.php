<div id="layoutSidenav_nav" style="background-color: white">
    <nav class="sb-sidenav accordion" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{route('admin.dashboard')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#subadminMenu" aria-expanded="false" aria-controls="employeesMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                    Subadmin
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="subadminMenu" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{route('cordinator.create')}}">Add Subadmin</a>
                        <a class="nav-link" href="{{route('cordinator.index')}}">Subadmins</a>
                    </nav>
                </div>

                <!-- Employees -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#employeesMenu" aria-expanded="false" aria-controls="employeesMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                    Employees
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="employeesMenu" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{route('subadmin.index')}}">Register Teacher</a>
                        <a class="nav-link" href="{{route('teachers.index')}}">Teachers</a>
                        <a class="nav-link" href="{{route('salary.index')}}">Manage Salary</a>
                        <a class="nav-link" href="{{route('employee_attendence')}}">Employee Attendance</a>
                    </nav>
                </div>

                <!-- Students -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#studentsMenu" aria-expanded="false" aria-controls="studentsMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-reader"></i></div>
                    Students
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="studentsMenu" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{route('students.index')}}">Registered Students</a>
                        <a class="nav-link" href="{{route('admissions.index')}}">Admission Records</a>
                        <a class="nav-link" href="{{route('fees.index')}}">Fees of CadetColleges</a>
                        <a class="nav-link" href="{{route('students_attendence')}}">Student Attendance</a>
                    </nav>
                </div>

                <!-- Academic -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#academicMenu" aria-expanded="false" aria-controls="academicMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-school"></i></div>
                    Academic
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="academicMenu" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{route('college.index')}}">College</a>
                        <a class="nav-link" href="{{route('class.index')}}">Class</a>
                        <a class="nav-link" href="{{route('subject.index')}}">Subject</a>
                    </nav>
                </div>
  <!-- Rules -->
  <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#rulesMenu" aria-expanded="false" aria-controls="academicMenu">
    <div class="sb-nav-link-icon"><i class="fas fa-balance-scale"></i></div>
    Rules and Regulations
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>

<div class="collapse" id="rulesMenu" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav">
        <a class="nav-link" href="{{route('studentcondition.index')}}">Student Rules</a>
        <a class="nav-link" href="{{route('employeecondition.index')}}">Teacher Rules</a>

    </nav>
</div>
                <!-- Exams -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#examsMenu" aria-expanded="false" aria-controls="examsMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Exams
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="examsMenu" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{route('exams.index')}}">Exam</a>
                        <a class="nav-link" href="{{route('schedule.list')}}">Exam Schedule</a>
                        <a class="nav-link" href="{{route('result')}}">Result</a>
                        <a class="nav-link" href="{{route('result-list')}}">Result List</a>
                    </nav>
                </div>

                <!-- Blogs -->
                <a class="nav-link" href="{{route('blogs.index')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-blog"></i></div>
                    Blogs
                </a>
                <a class="nav-link" href="{{route('monthlyfee.index')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                  Monthly Fee
                </a>
                 <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#testMenu" aria-expanded="false" aria-controls="testMenu">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    PAF Pannel Login
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="testMenu" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://highbrowsian.com/pafpannel/login.php">Admin</a>
                        <a class="nav-link" href="https://highbrowsian.com/pafpannel/register.php">Register</a>
                       
                    </nav>
                </div>
                <a class="nav-link" href="{{route('expenses.index')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                  Manage Expenses
                </a>
            </div>
        </div>
    </nav>
</div>
