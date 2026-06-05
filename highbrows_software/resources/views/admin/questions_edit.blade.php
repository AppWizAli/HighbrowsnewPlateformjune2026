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

  <div id="layoutSidenav_content">
    <main>
      <div class="container mt-5">
        <div class="card shadow-lg">
          <div class="card-header text-white" style="background-color: #084298;">
            <h4>Edit Question</h4>
          </div>
          <div class="card-body">
            <form action="{{ route('questions.update', $question->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $question->subject) }}" required>
              </div>

              <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <input type="number" class="form-control" id="grade" name="grade" value="{{ old('grade', $question->grade) }}" required>
                <select name="grade" id="grade" class="form-select">

                    @for ($g = 5; $g <= 12; $g++)
                      <option value="{{ $g }}" {{ old('grade', $question->grade) == $g ? 'selected' : '' }}>Grade-{{ $g }}</option>
                    @endfor
                    
                  </select>
              </div>


              <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <textarea class="form-control" id="question" name="question" rows="3" required>{{ old('question', $question->question) }}</textarea>
              </div>

              @php
                $options = $question->options;
              @endphp

              @foreach(['A', 'B', 'C', 'D'] as $option)
                <div class="mb-3">
                  <label for="option_{{ $option }}" class="form-label">Option {{ $option }}</label>
                  <input type="text" class="form-control" name="options[{{ $option }}]" value="{{ old("options.$option", $options[$option]) }}" required>
                </div>
              @endforeach

              <div class="mb-3">
                <label for="correct_answer" class="form-label">Correct Answer</label>
                <select class="form-select" name="correct_answer" required>
                  <option value="">Select</option>
                  @foreach(['A', 'B', 'C', 'D'] as $opt)
                    <option value="{{ $opt }}" {{ $question->correct_answer == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                  @endforeach
                </select>
              </div>

              <button type="submit" class="btn btn-success">Update Question</button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

@include('admin.footer')
