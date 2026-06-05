@include('admin.head')
<style>
    .student-info {
        text-align: center;
        margin-bottom: 20px;
    }
    .student-info img {
        border-radius: 50%;
        width: 100px;
        height: 100px;
    }
    .student-info .details {
        margin-top: 10px;
    }
    .result-table th, .result-table td {
        text-align: center;
        vertical-align: middle;
    }

    .result-summary {
background-color: #f8f9fa;
padding: 10px;
border-radius: 5px;
text-align: center;
}

.card {
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
border: none;
}
</style>
@include('admin.nav')  
@php($currentUserType = optional(auth()->user())->usertype)
<div id="layoutSidenav">
    @if($currentUserType == 'admin')
        @include('admin.sidebar') <!-- Include the Admin Sidebar -->
    @elseif($currentUserType == 'subadmin')
        @include('subadmin.sidebar') <!-- Include the Subadmin Sidebar -->
    @else
    @include('student.sidebar') 
    @endif

    <!-- Container for the form, adjusted to the right of the sidebar -->
    <div id="layoutSidenav_content">
        <main>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="container">
                        <!-- Add New Exam Button -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <form id="searchForm" method="GET" action="" class="mb-4 w-100">
                                        <div class="form-group">
                                            <select name="exam" id="exam" class="form-control w-50">
                                                <option value="" disabled selected>Select Exam</option>
                                                @foreach ($exams as $exam)
                                                    <option value="{{ $exam->id }}" {{ $exam->id == request()->query('exam') ? 'selected' : '' }}>
                                                        {{ $exam->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <!-- Hidden Student ID -->
                                            <button type="submit"
                                                class="btn btn-light btn-outline-primary mt-3">Search</button>
                                        
                                        </div>

                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- Filter Input -->
                    <div class="container ">
                        <div class="row">
                            <!-- Student Info -->
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <img src="{{ $student->passport_pic ? asset('storage/' . ltrim($student->passport_pic, '/')) : asset('highbroimage/people.svg') }}" class="rounded-circle"
                                        alt="Student Image" height="150px">
                                    <h4 class="mt-2">{{ $student->full_name ?? $student->name ?? 'Student' }}</h4>
                                    <p>Registration No: {{ $student->custom_id }}</p>
                                    <p>Class: {{ $student->grade->name }}</p>
                       
                                </div>
                            </div>
                            <!-- Result Table -->
                            <!-- Result Table -->
                            <div class="card ml-5">

                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Obtained Marks</th>
                                                <th>Total Marks</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalmarks = 0;
                                                $obt = 0;
                                                $avg = 0;
                                            @endphp
                                        
                                            @foreach ($results as $result)
                                                <tr class="bg-white">
                                                    <td>{{ $result->subject->subj_name }}</td>
                                                    <td>{{ $result->obt_marks }}</td>
                                                    <td>{{ $result->total }}</td>
                                                    <td>{{ $result->grade }}</td>
                                                </tr>
                                                @php
                                                    $totalmarks += $result->total;
                                                    $obt += $result->obt_marks;
                                                @endphp
                                            @endforeach
                                        
                                            @php
                                                $avg = $totalmarks > 0 ? ($obt / $totalmarks * 100) : 0;
                                            @endphp
                                        
                                            @if ($totalmarks == 0)
                                                <tr>
                                                    <td colspan="4" class="text-center text-danger">Exam is not attempted yet</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        
                                    </table>
                                    <div class="result-summary mt-4">
                                        <p>Total Marks: {{ $totalmarks }}</p>
                                        <p>Total Obtained Marks: {{ $obt }}</p>
                                        <p>Percentage: {{ number_format($avg, 2) }}%</p>
                                    </div>





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
