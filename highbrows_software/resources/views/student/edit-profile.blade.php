@include('admin.head')
@include('admin.nav')  
@php($currentUserType = optional(auth()->user())->usertype)
<div id="layoutSidenav">
    @if($currentUserType == 'admin')
    @include('admin.sidebar') 
@elseif($currentUserType == 'subadmin')
    @include('subadmin.sidebar') 
@else
@include('student.sidebar') 
@endif 

    <!-- Container for the form, adjusted to the right of the sidebar -->
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <div class="card shadow-lg">
                    <div class="card-header  text-white " style="background-color: #084298">
                        <h4>Edit Profile</h4>
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
    <div class="alert alert-info">
        {{ session('message') }}
    </div>
@endif

<form id="registrationForm" method="POST" action="{{ route('profile.update', $user->id) }}" class="needs-validation" novalidate>
    @csrf
    @method('PUT') <!-- Method for update -->
    
    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" id="username" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        @error('name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="contact" class="form-label">Contact Number:</label>
        <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact', $user->contact) }}" required>
        @error('contact')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" class="form-control">
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Leave empty if you do not want to change the password.</small>
    </div>

    <button type="submit" class="btn text-white" style="background-color:#084298;">Update</button>
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
