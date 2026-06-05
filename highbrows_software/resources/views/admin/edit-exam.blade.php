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
                    <h4 class="card-title my-4">Edit Exam</h4>
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-book"></i> Exam</h4>
                    </div>
                    <div class="card-body bg-light">
                        <form class="mt-4" action="{{ route('exams.update', $exam->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="mb-3">
                            <label for="examName" class="form-label">Exam Name <span class="text-danger">*</span></label>
                            <input
                              type="text"
                              class="form-control"
                              id="examName"
                              name="name"
                              placeholder="Enter exam name"
                              value="{{ old('name', $exam->name) }}"
                              required
                            />
                          </div>
                      
                          <div class="mb-3">
                            <label for="examNote" class="form-label">Note</label>
                            <textarea
                              class="form-control"
                              id="examNote"
                              rows="3"
                              name="note"
                              placeholder="Enter any notes"
                            >{{ old('note', $exam->note) }}</textarea>
                          </div>
                      
                          <button type="submit" class="btn" style="background-color: #084298; color: white;">
                            Save Changes
                          </button>
                        </form>
                      </div>
                      
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.footer')
