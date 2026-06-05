<div id="layoutSidenav_nav" style="background-color: white">
    <nav class="sb-sidenav accordion " id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                {{-- @if ($fee && $fee->status==='paid')
    <a class="nav-link" href="{{ route('students.create') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
        Admission Form
    </a>
@endif --}}
                <a class="nav-link" href="{{route('student.dashboard')}}" >
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="{{route('student.profile')}}" >
                    <div class="sb-nav-link-icon"><i class="fas fa-user-circle"></i></div>
                    Profile
                </a>
                <a class="nav-link" href="{{route('profile.edit', ['id' => Auth::user()->id])}}" >
                    <div class="sb-nav-link-icon"><i class="fas fa-user-edit"></i></div>
                    Edit Profile
                </a>
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#termsModal">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                    Terms & Conditions
                </a>

                <a class="nav-link" href="{{ route('fee.detail', ['id' => Auth::user()->id]) }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>
                    Cadet Colleges Fee
                </a>
                <a class="nav-link" href="{{ route('student.result') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    View Result
                </a>
                <a class="nav-link" href="{{ route('student.attendance') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                    View Attendance
                </a>
                <a class="nav-link" href="{{ route('monthlyfee.detail', ['id' => Auth::user()->id]) }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>
                   Fee Record
                </a>
                <a class="nav-link" href="https://highbrowsian.com/pafpannel/userlogin.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-sign-in"></i></div>
                   Online Exam login
                </a>

            </div>
        </div>

    </nav>
</div>
