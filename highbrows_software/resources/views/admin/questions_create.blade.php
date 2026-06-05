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
            <h4>Add New Question</h4>
          </div>
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form method="POST" action="{{ route('questions.store') }}">
              @csrf

              <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
              </div>

              <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <select name="grade" id="grade" class="form-select" required>

                    @for ($g = 5; $g <= 12; $g++)
                      <option value="{{ $g }}" >Grade-{{ $g }}</option>
                    @endfor

                  </select>
              </div>

              <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
              </div>

              @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                <div class="mb-3">
                  <label for="option_{{ $opt }}" class="form-label">Option {{ $opt }}</label>
                  <input type="text" class="form-control" id="option_{{ $opt }}" name="options[{{ $opt }}]" required>
                </div>
              @endforeach

              <div class="mb-3">
                <label for="correct_answer" class="form-label">Correct Answer</label>
                <select class="form-select" id="correct_answer" name="correct_answer" required>
                  <option value="">Select the correct option</option>
                  @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                  @endforeach
                </select>
              </div>

              <button type="submit" class="btn btn-primary">Add Question</button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

@include('admin.footer')
