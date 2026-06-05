@include('admin.head')
@include('admin.nav')

<div id="layoutSidenav">
    {{-- Sidebar based on user type --}}
    @if(auth()->user()->usertype == 'admin')
        @include('admin.sidebar')
    @elseif(auth()->user()->usertype == 'subadmin')
        @include('subadmin.sidebar')
    @elseif(auth()->user()->usertype == 'cordinator')
        @include('cordinator.sidebar')
    @else
        @include('student.sidebar')
    @endif

    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-5">
                <div class="d-flex justify-content-start">
                    <h4 class="card-title my-4">Add Result</h4>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background-color: #084298">
                        <h4><i class="fas fa-pencil-alt"></i> Results</h4>
                    </div>

                    <div class="card-body bg-light">
                        <form class="mt-1" action="{{ route('result-store') }}" method="POST">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-md-3 px-5">
                                    <label for="exam">Exam Name</label>
                                    <select class="form-control" name="exam" required>
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="class">Class Name</label>
                                    <select class="form-control" name="class" required>
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="subject">Subject</label>
                                    <select class="form-control" name="subject" required>
                                        <option value="{{ $subject->id }}">{{ $subject->subj_name }}</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Optional shared total marks input --}}
                            <div class="row mt-4 px-5">
                                <div class="col-md-3">
                                    <label for="shared_total">Set Total Marks for All (optional):</label>
                                    <input type="number" id="shared_total" class="form-control">
                                </div>
                            </div>

                            {{-- Student Marks Table --}}
                            @if($students->isNotEmpty())
                                <div class="container-fluid bg-light mt-4 p-3" style="border-radius: 15px">
                                    <div class="row fw-bold">
                                        <div class="col-md-3 px-5">
                                            <label>Student</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Marks</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Obtained Marks</label>
                                        </div>
                                    </div>

                                    @foreach ($students as $student)
                                        <div class="row align-items-center mt-2">
                                            <div class="col-md-3 px-5">
                                                <input type="hidden" name="students[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                                <input type="text" class="form-control" value="{{ $student->full_name }}" disabled>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number" class="form-control total-input" name="students[{{ $loop->index }}][total]" required>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="students[{{ $loop->index }}][obt_marks]" required>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">Add Marks</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.footer')

{{-- Optional JavaScript to set shared total marks --}}
<script>
    document.getElementById('shared_total').addEventListener('input', function () {
        let total = this.value;
        document.querySelectorAll('.total-input').forEach(input => {
            input.value = total;
        });
    });
</script>
