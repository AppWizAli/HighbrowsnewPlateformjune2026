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
                <div class="d-flex justify-content-start">
                    <h4 class="card-title my-4">Add New Subject</h4>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Subject</h4>
                     
                    </div>
                    <div class="card-body bg-light">
           
                        <form action="{{ route('subject.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="subject_name" class="form-label">Subject Name</label>
                                        <input type="text" class="form-control {{ $errors->has('subj_name') ? 'is-invalid' : '' }}" id="subject_name" name="subj_name" value="{{ old('subj_name') }}">
                                        @error('subj_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="type" class="form-label">Subject Type</label>
                                        <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" id="type" name="type">
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="Mandatory" {{ old('type') == 'Mandatory' ? 'selected' : '' }}>Mandatory</option>
                                            <option value="Optional" {{ old('type') == 'Optional' ? 'selected' : '' }}>Optional</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="pass_marks" class="form-label">Pass Marks</label>
                                        <input type="text" class="form-control {{ $errors->has('pass_marks') ? 'is-invalid' : '' }}" id="pass_marks" name="pass_marks" value="{{ old('pass_marks') }}">
                                        @error('pass_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="final_marks" class="form-label">Total Marks</label>
                                        <input type="text" class="form-control {{ $errors->has('total_marks') ? 'is-invalid' : '' }}" id="final_marks" name="total_marks" value="{{ old('total_marks') }}">
                                        @error('total_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn px-4 text-white" style="background-color: #084298">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>


        </div>
        @include('admin.footer') 
