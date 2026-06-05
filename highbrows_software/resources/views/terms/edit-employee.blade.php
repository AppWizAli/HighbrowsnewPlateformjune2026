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
                    <h4 class="card-title my-4">Edit Rules</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Terms and Conditions</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form action="{{ route('employeecondition.update', $rule->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                
                         
                
                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Rules</label>
                                <textarea class="form-control" id="description" name="rules">{{ $rule->rules }}</textarea>
                            </div>
                
                            <!-- Submit Button -->
                            <button type="submit" class="btn text-white" style="background-color: #084298">Update</button>
                            <a href="{{ route('employeecondition.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('description');
</script>
@include('admin.footer')
