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
                    <h4 class="card-title my-4">Edit Teacher</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Teacher</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form action="{{ route('teachers.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-white" style="background-color: #084298;">
                                    <h4 class="mb-0">Update Employee Details</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Name Field -->
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input 
                                            type="text" 
                                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                                            id="name" 
                                            name="name" 
                                            value="{{ $employee->name }}" 
                                            required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <!-- Date of Birth Field -->
                                    <div class="form-group mb-3">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input 
                                            type="date" 
                                            class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}" 
                                            id="dob" 
                                            name="date_of_birth" 
                                            value="{{ $employee->date_of_birth }}" 
                                            required>
                                        @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <!-- Gender Field -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Gender</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="radio" 
                                                    name="gender" 
                                                    id="genderMale" 
                                                    value="Male" 
                                                    {{ $employee->gender == 'Male' ? 'checked' : '' }} 
                                                    required>
                                                <label class="form-check-label" for="genderMale">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="radio" 
                                                    name="gender" 
                                                    id="genderFemale" 
                                                    value="Female" 
                                                    {{ $employee->gender == 'Female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="genderFemale">Female</label>
                                            </div>
                                        </div>
                                        @error('gender')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <!-- Email Field -->
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input 
                                            type="email" 
                                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                            id="email" 
                                            name="email" 
                                            value="{{ $employee->email }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <!-- Phone Field -->
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input 
                                            type="text" 
                                            class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" 
                                            id="phone" 
                                            name="phone" 
                                            value="{{ $employee->phone }}">
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <!-- Address Field -->
                                    <div class="form-group mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea 
                                            class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" 
                                            id="address" 
                                            name="address" 
                                            rows="3" 
                                            required>{{ $employee->address }}</textarea>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <!-- Joining Date Field -->
                                    <div class="form-group mb-3">
                                        <label for="joining_date" class="form-label">Joining Date</label>
                                        <input 
                                            type="date" 
                                            class="form-control {{ $errors->has('joining_date') ? 'is-invalid' : '' }}" 
                                            id="joining_date" 
                                            name="joining_date" 
                                            value="{{ $employee->joining_date }}">
                                        @error('joining_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <!-- Image Field -->
                                    <div class="form-group mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input 
                                            type="file" 
                                            class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" 
                                            id="image" 
                                            name="image" 
                                            accept="image/*">
                                        <div class="mt-2">
                                            <img 
                                                src=" {{ Storage::disk('public')->url($employee->image)}}" 
                                                alt="Employee Image" 
                                                class="img-thumbnail" 
                                                style="width: 100px; height: 100px;">
                                        </div>
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn " style="background-color: #084298;color:white;">Update</button>
                                </div>
                            </div>
                        </form>
                        
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.footer')
