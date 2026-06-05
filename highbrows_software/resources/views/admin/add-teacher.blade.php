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
                        <h2>Add Teacher</h2>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
@if (session('message'))
{{session('message')}}
@endif
<form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Hidden Input for ID (if applicable) -->
    <input type="text" name="user_id" value="{{ old('user_id', request()->query('id')) }}" hidden>




    <div class="form-group row mb-3">
        <label for="name" class="col-sm-2 col-form-label">Full Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" value="{{ old('name') }}">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label for="dob" class="col-sm-2 col-form-label">Date of Birth</label>
        <div class="col-sm-10">
            <input type="date" class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}" id="dob" name="date_of_birth" value="{{ old('date_of_birth') }}">
            @error('date_of_birth')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-sm-2 col-form-label">Gender</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }}>
                <label class="form-check-label" for="genderMale">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                <label class="form-check-label" for="genderFemale">Female</label>
            </div>
            @error('gender')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label for="phone" class="col-sm-2 col-form-label">Phone Number</label>
        <div class="col-sm-10">
            <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone') }}">
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label for="address" class="col-sm-2 col-form-label">Address</label>
        <div class="col-sm-10">
            <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" id="address" name="address" rows="3">{{ old('address') }}</textarea>
            @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label for="joining_date" class="col-sm-2 col-form-label">Joining Date</label>
        <div class="col-sm-10">
            <input type="date" class="form-control {{ $errors->has('joining_date') ? 'is-invalid' : '' }}" id="joining_date" name="joining_date" value="{{ old('joining_date') }}">
            @error('joining_date')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label for="salary" class="col-sm-2 col-form-label">Salary</label>
        <div class="col-sm-10">
            <input type="number" class="form-control {{ $errors->has('salary') ? 'is-invalid' : '' }}" id="salary" name="salary" value="{{ old('salary') }}">
            @error('salary')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-3">
        <label for="image" class="col-sm-2 col-form-label">Image</label>
        <div class="col-sm-10">
            <input type="file" class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image" name="image" accept="image/*">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-10 offset-sm-2">
            <button type="submit" class="btn  px-4 py-2" style="background-color: #084298;color:white;">Save</button>
        </div>
    </div>
</form>





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
