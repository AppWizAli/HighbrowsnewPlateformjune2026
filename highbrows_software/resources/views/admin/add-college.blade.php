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
                    <h4 class="card-title my-4">Add New College</h4>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> College</h4>
                     
                    </div>
                    <div class="card-body bg-light">
           
                        <form action="{{ route('college.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="subject_name" class="form-label">College Name</label><span style="color: red"> *</span>
                                        <input type="text" class="form-control {{ $errors->has('college_name') ? 'is-invalid' : '' }}" id="college_name" name="college_name" value="{{ old('college_name') }}">
                                        @error('college_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                              
                           <div class="col-md-6">
    <div class="form-group mb-3">
        <label for="college_fee" class="form-label">College Fee</label><span style="color: red"> *</span>
        <input type="number" class="form-control {{ $errors->has('college_fee') ? 'is-invalid' : '' }}" id="college_fee" name="college_fee" value="{{ old('college_fee') }}" step="0.01" min="0">
        @error('college_fee')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
                            <div >
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
