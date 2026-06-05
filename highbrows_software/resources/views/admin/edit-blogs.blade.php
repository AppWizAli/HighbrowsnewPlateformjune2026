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
                    <h4 class="card-title my-4">Edit Blog</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Blog</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                
                            <!-- Heading -->
                            <div class="mb-3">
                                <label for="heading" class="form-label">Heading</label>
                                <input type="text" class="form-control" id="heading" name="heading" value="{{ $blog->heading }}" required>
                            </div>
                
                            <!-- Existing Image Preview -->
                            <div class="mb-3">
                                <label class="form-label">Current Image</label><br>
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="Blog Image" width="150">
                            </div>
                
                            <!-- New Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload New Image (Optional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                
                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ $blog->description }}</textarea>
                            </div>
                
                            <!-- Submit Button -->
                            <button type="submit" class="btn text-white" style="background-color: #084298">Update</button>
                            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.footer')
