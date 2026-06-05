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
                        <h4>Blog</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{route('blogs.index')}}" method="POST" enctype="multipart/form-data">
               @csrf 
    <!-- Heading -->
    <div class="mb-3">
        <label for="heading" class="form-label">Heading</label>
        <input type="text" class="form-control" id="heading" name="heading" placeholder="Enter heading" required>
    </div>

    <!-- Image Upload -->
    <div class="mb-3">
        <label for="image" class="form-label">Upload Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
    </div>

    <!-- Description -->
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn text-white" style="background-color: #084298">Submit</button>
</form>

                    </div>
                </div>
            </div>
        </main>
    </div>
    
</div>


        </div>
     <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('description'); // Match your textarea ID
</script>

        
        @include('admin.footer') 
