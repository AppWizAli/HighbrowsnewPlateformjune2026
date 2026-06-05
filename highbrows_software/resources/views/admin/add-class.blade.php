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
                    <h4 class="card-title my-4">Add New Class</h4>
                    {{-- <button class="btn my-4 text-white p-2 " style="background-color: #084298">Add New Subject</button> --}}
                </div>
              
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> Class</h4>
                     
                    </div>
                    <div class="card-body bg-light">
           
                        <form class="mt-4" action="{{ route('class.store') }}" method="POST">
                            @csrf
                        
                            <div class="mb-3">
                                <label for="examName" class="form-label">Class Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="examName" name="name" placeholder="Enter class name" required maxlength="255" value="{{ old('name') }}">
                        
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="mb-3">
                                <label class="form-label">Select Subjects <span class="text-danger">*</span></label>
                                <div class="form-check ml-5">
                                    @foreach ($subjects as $subject)
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input @error('subject_id') is-invalid @enderror" 
                                                type="checkbox" 
                                                name="subject_id[]" 
                                                value="{{ $subject->id }}" 
                                                id="subject-{{ $subject->id }}" 
                                                {{ in_array($subject->id, old('subject_id', [])) ? 'checked' : '' }}>
                        
                                            <label class="form-check-label" for="subject-{{ $subject->id }}">
                                                {{ $subject->subj_name }}
                                            </label>
                                        </div>
                                    @endforeach
                        
                                    @error('subject_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
<div class="mb-3">
    <label for="teachers" class="form-label">Assign a Teacher to Class</label>
    <select 
    class="form-control @error('teacher_id') is-invalid @enderror" 
    name="teacher_id" 
    id="teacher-select"

>
    @foreach ($teachers as $teacher)
        <option 
            value="{{ $teacher->id }}" 
            {{ (string) old('teacher_id') === (string) $teacher->id ? 'selected' : '' }}>
            {{ $teacher->name }}
        </option>
    @endforeach
</select>
</div>

                            <div class="mb-3">
                                <label for="examNote" class="form-label">Note</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="examNote" rows="3" name="note" placeholder="Enter any notes" maxlength="500">{{ old('note') }}</textarea>
                        
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <button type="submit" class="btn" style="background-color: #084298; color: white;">Add Class</button>
                        </form>
                        
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>


        </div>
        @include('admin.footer') 
