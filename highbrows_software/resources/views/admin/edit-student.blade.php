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

    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <div class="card shadow-lg">
                    <div class="card-header text-white text-center" style="background-color: #084298">
                        <h4>Edit Admission Form</h4>
                    </div>
                    <div class="card-body">

                        <form id="admissionForm" action="{{ route('admissions.update', $admission->id) }}" enctype="multipart/form-data" method="post">
                            @csrf
                            @method('PUT') <!-- Required for PUT request when updating data -->
                            
                            <!-- Step 1 -->
                            <h4 class=" mb-3" style="color: #084298">Personal Information</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $admission->full_name) }}">
                                    @error('full_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="father_name">Father Name</label>
                                    <input type="text" class="form-control" id="father_name" name="father_name" value="{{ old('father_name', $admission->father_name) }}">
                                    @error('father_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="mother_name">Mother Name</label>
                                    <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ old('mother_name', $admission->mother_name) }}">
                                    @error('mother_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="student_cnic">Student B-Form Number</label>
                                    <input type="text" class="form-control" id="student_cnic" name="student_cnic" value="{{ old('student_cnic', $admission->student_cnic) }}">
                                    @error('student_cnic')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="mother_cnic">Mother CNIC</label>
                                    <input type="text" class="form-control" id="mother_cnic" name="mother_cnic" value="{{ old('mother_cnic', $admission->mother_cnic) }}">
                                    @error('mother_cnic')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="father_cnic">Father CNIC</label>
                                    <input type="text" class="form-control" id="father_cnic" name="father_cnic" value="{{ old('father_cnic', $admission->father_cnic) }}">
                                    @error('father_cnic')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        
                            <hr class="my-4">
                        
                            <!-- Step 2 -->
                            <h4 class="mb-3" style="color: #084298">Additional Information</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="guardian_name">Guardian Name (If father is deceased)</label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $admission->guardian_name) }}">
                                    @error('guardian_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" value="{{ old('religion', $admission->religion) }}">
                                    @error('religion')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="pre_class">Previous Class</label>
                                    <input type="text" class="form-control" id="pre_class" name="pre_class" value="{{ old('pre_class', $admission->pre_class) }}">
                                    @error('pre_class')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="grade_applied_for">Grade Applied For</label>
                                    <select class="form-control" id="grade_applied_for" name="grade_applied_for">
                                        <option value="">Select Grade</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('grade_applied_for', $admission->grade_applied_for) == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grade_applied_for')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $admission->dob) }}">
                                    @error('dob')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="res_type">Residence Type</label>
                                    <select class="form-control" id="res_type" name="res_type">
                                        <option value="">Select</option>
                                        <option value="Hostel" {{ old('res_type', $admission->res_type) == 'Hostel' ? 'selected' : '' }}>Hostelite</option>
                                        <option value="DayScholar" {{ old('res_type', $admission->res_type) == 'DayScholar' ? 'selected' : '' }}>Day Scholar</option>
                                        <option value="Online" {{ old('res_type', $admission->res_type) == 'Online' ? 'selected' : '' }}>online</option>
                                    </select>
                                    @error('res_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        
                            <hr class="my-4">
                        
                            <!-- Step 3 -->
                            <h4 class="mb-3" style="color: #084298">Contact Information</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="contact">Father's Contact</label>
                                    <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact', $admission->contact) }}">
                                    @error('contact')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="guardian_phone">Mother's Contact</label>
                                    <input type="text" class="form-control" id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone', $admission->guardian_phone) }}">
                                    @error('guardian_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="guardian_whatsapp">WhatsApp Number</label>
                                    <input type="text" class="form-control" id="guardian_whatsapp" name="guardian_whatsapp" value="{{ old('guardian_whatsapp', $admission->guardian_whatsapp) }}">
                                    @error('guardian_whatsapp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="guardian_contact">Guardian Contact (If father is deceased)</label>
                                    <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact', $admission->guardian_contact) }}">
                                    @error('guardian_contact')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="domicile">Domicile District</label>
                                    <input type="text" class="form-control" id="domicile" name="domicile" value="{{ old('domicile', $admission->domicile) }}">
                                    @error('domicile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="postal_address">Postal Address</label>
                                    <textarea class="form-control" id="postal_address" name="postal_address">{{ old('postal_address', $admission->postal_address) }}</textarea>
                                    @error('postal_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="father_income">Father's Income</label>
                                    <input type="text" class="form-control" id="father_income" name="father_income" value="{{ old('father_income', $admission->father_income) }}">
                                    @error('father_income')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        
                            <hr class="my-4">
                        
                            <!-- Step 4 -->
                            <h4 class=" mb-3" style="color: #084298">Documents</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="passport_pic">Passport Size Picture</label>
                                    @if($admission->passport_pic)
                                        <a href="{{ asset('storage/app/public/'.$admission->passport_pic) }}" target="_blank">View current picture</a>
                                    @endif
                                    <input type="file" class="form-control" id="passport_pic" name="passport_pic">
                                    @error('passport_pic')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="b_form">Student's B-Form</label>
                                    @if($admission->b_form)
                                        <a href="{{ asset('storage/app/public/'.$admission->b_form) }}" target="_blank">View current B-form</a>
                                    @endif
                                    <input type="file" class="form-control" id="b_form" name="b_form">
                                    @error('b_form')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="father_cnic_doc">Father CNIC (Document)</label>
                                    @if($admission->father_cnic_doc)
                                        <a href="{{ asset('storage/app/public/'.$admission->father_cnic_doc) }}" target="_blank">View current CNIC document</a>
                                    @endif
                                    <input type="file" class="form-control" id="father_cnic_doc" name="father_cnic_doc">
                                    @error('father_cnic_doc')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="result_card">Last Result Card</label>
                                    @if($admission->result_card)
                                        <a href="{{ asset('storage/app/public/'.$admission->result_card) }}" target="_blank">View current result card</a>
                                    @endif
                                    <input type="file" class="form-control" id="result_card" name="result_card">
                                    @error('result_card')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        
                            <hr class="my-4">
                        
                            <!-- Checkbox to Apply for Cadet Colleges -->
                            <h4 class=" mb-3" style="color: #084298">Cadet College Application</h4>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>
                                        <input type="checkbox" id="apply_cadet_colleges" name="apply_cadet_colleges" value="1" 
                                        {{ old('apply_cadet_colleges', $admission->apply_cadet_colleges) == 1 ? 'checked' : '' }}>
                                        Do you want to apply for Cadet Colleges?
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Static List of Cadet Colleges (Initially Hidden) -->
                            <div id="cadetCollegesList" class="mt-3" style="display: {{ old('apply_cadet_colleges', $admission->apply_cadet_colleges) == 1 ? 'block' : 'none' }}">
                                <label>Select the Cadet Colleges you want to apply for:</label>
                                <div class="row g-3">
                                    @foreach ($colleges as $college)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="cadet_colleges[]" value="{{ $college->id }}" 
                                            {{ in_array($college->id, old('cadet_colleges', $admission->cadetColleges->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="college_{{ $college->id }}">{{ $college->college_name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg" style="background-color: #084298; color:white">Update</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
    document.getElementById('apply_cadet_colleges').addEventListener('change', function() {
        document.getElementById('cadetCollegesList').style.display = this.checked ? 'block' : 'none';
    });
</script>
@include('admin.footer')
