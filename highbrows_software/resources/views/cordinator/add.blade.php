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
                        <h4>Register Cordinator</h4>
                    </div>
                    <div class="card-body">
                        {{-- @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif --}}
<form id="registrationForm" method="POST" action="{{ route('cordinator.store') }}">
    @csrf
   <div class="mb-3">
    <label for="username" class="form-label">Username:</label>
    <input type="text" id="username" name="name" class="form-control" value="{{ old('name') }}" required>
    @error('name')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label for="contact" class="form-label">Contact Number:</label>
    <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact') }}" required>
    @error('contact')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
    @error('email')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">Password:</label>
    <input type="password" id="password" name="password" class="form-control" required>
    @error('password')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


    <button type="submit" class="btn  " style="background-color: #084298;color:white">Register</button>
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
