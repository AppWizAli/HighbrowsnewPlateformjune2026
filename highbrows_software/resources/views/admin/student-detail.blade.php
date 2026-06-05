@include('admin.head')
@include('admin.nav')  
@php($currentUserType = optional(auth()->user())->usertype)
<div id="layoutSidenav">
    @if($currentUserType == 'admin')
    @include('admin.sidebar') 
@elseif($currentUserType == 'subadmin')
    @include('subadmin.sidebar') 
    @elseif($currentUserType == 'cordinator')
    @include('cordinator.sidebar') 
@else
@include('student.sidebar') 
@endif 

    <!-- Container for the form, adjusted to the right of the sidebar -->
    <div id="layoutSidenav_content">
        <main>
            <div class="container">
                <div class="d-flex justify-content-start">
                    {{-- <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('teachers.create') }}">Add New Admission</a> --}}
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
        
                        
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Student Profile</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                          
                            <div class="card-body">
                                <div class="text-center mb-4"><img src="{{ $student->passport_pic ? asset('storage/' . ltrim($student->passport_pic, '/')) : asset('highbroimage/people.svg') }}" alt="Student Image" class="img-fluid employee-image mt-5 " style="max-width: 150px;">
                                    <div class="employee-name">{{ $student->full_name }} | {{ $student->custom_id }}</div></div>
                                    
                                {{-- <h4 class="card-title mb-4">Teachers List</h4> --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                       
                                        <tr>
                                            <th>Father's Name</th>
                                            <td>{{ $student->father_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mother's Name</th>
                                            <td>{{ $student->mother_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Student CNIC</th>
                                            <td>{{ $student->student_cnic }}</td>
                                        </tr>
                                        <tr>
                                            <th>Father CNIC</th>
                                            <td>{{ $student->father_cnic }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mother CNIC</th>
                                            <td>{{ $student->mother_cnic }}</td>
                                        </tr>
                                        <tr>
                                            <th>Guardian Name</th>
                                            <td>{{ $student->guardian_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Religion</th>
                                            <td>{{ $student->religion }}</td>
                                        </tr>
                                        <tr>
                                            <th>Previous Class</th>
                                            <td>{{ $student->pre_class }}</td>
                                        </tr>
                                        <tr>
                                            <th>Grade Applied For</th>
                                            <td>{{ $student->grade ? $student->grade->name : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td>{{ $student->dob }}</td>
                                        </tr>
                                        <tr>
                                            <th>Admission Date</th>
                                            <td>{{ $student->admission_date }}</td>
                                        </tr>
                                        <tr>
                                            <th>Residential Type</th>
                                            <td>{{ $student->res_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact Number</th>
                                            <td>{{ $student->contact }}</td>
                                        </tr>
                                        <tr>
                                            <th>Guardian Phone</th>
                                            <td>{{ $student->guardian_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Guardian WhatsApp</th>
                                            <td>{{ $student->guardian_whatsapp ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Guardian Contact</th>
                                            <td>{{ $student->guardian_contact ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Domicile</th>
                                            <td>{{ $student->domicile }}</td>
                                        </tr>
                                        <tr>
                                            <th>Postal Address</th>
                                            <td>{{ $student->postal_address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Father's Income</th>
                                            <td>{{ number_format($student->father_income, 2) }}</td>
                                        </tr>
                                
                                        <!-- Display Applied Cadet Colleges -->
                                        <tr>
                                            <th>Applied Cadet Colleges</th>
                                            <td>
                                                @if($student->apply_cadet_colleges)
                                                    @if($student->cadetColleges->isEmpty())
                                                        <span class="text-muted">No cadet colleges applied</span>
                                                    @else
                                                        <ul>
                                                            @foreach($student->cadetColleges as $college)
                                                                <li>{{ $college->college_name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not applied for cadet colleges</span>
                                                @endif
                                            </td>
                                        </tr>
                                
                                        <!-- Display Uploaded Documents -->
                                    
                                        <tr>
                                            <th>B-Form</th>
                                            <td>
                                                @if($student->b_form)
                                                    <a href="{{ asset('storage/' . $student->b_form) }}" target="_blank">View B-Form</a>
                                                @else
                                                    <span class="text-muted">No B-Form</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Father CNIC Document</th>
                                            <td>
                                                @if($student->father_cnic_doc)
                                                    <a href="{{ asset('storage/' . $student->father_cnic_doc) }}" target="_blank">View CNIC Document</a>
                                                @else
                                                    <span class="text-muted">No CNIC Document</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Result Card</th>
                                            <td>
                                                @if($student->result_card)
                                                    <a href="{{ asset('storage/' . $student->result_card) }}" target="_blank">View Result Card</a>
                                                @else
                                                    <span class="text-muted">No Result Card</span>
                                                @endif
                                            </td>
                                        </tr>
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
