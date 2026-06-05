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
                    <h4 class="card-title my-4">Edit Profile</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> Subadmin</h4>
                    </div>
                    <div class="card-body bg-light">
           
                        <form method="POST" action="{{ route('cordinator.update', $subadmin->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $subadmin->name }}" required>
                            </div>
                
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact:</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="{{ $subadmin->contact }}" required>
                            </div>
                
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $subadmin->email }}" required>
                            </div>
                
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password (Leave blank if not changing):</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                
                            <button type="submit" class="btn " style="background-color: #084298;color:white;">Update</button>
                            <a href="{{ route('cordinator.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>

@include('admin.footer')
