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
      <div class="container">
        <div class="d-flex justify-content-start">
          <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('questions.create') }}">Add New Question</a>
        </div>
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card shadow-lg">
              <div class="card-header text-white" style="background-color: #084298;">
                <h4 class="mb-0"><i class="fas fa-question-circle"></i> Questions</h4>
              </div>

              @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
              @endif

              <div class="card-body">
                <form method="GET" action="{{ route('questions.index') }}" class="row g-3 mb-4 align-items-end">
                    <div class="col-md-3">
                      <label for="grade" class="form-label">Grade</label>
                      <select name="grade" id="grade" class="form-select">
                        <option value="">All Grades</option>
                        @for ($g = 5; $g <= 12; $g++)
                          <option value="{{ $g }}" {{ request('grade') == $g ? 'selected' : '' }}>Grade-{{ $g }}</option>
                        @endfor

                      </select>
                    </div>

                    <div class="col-md-3">
                      <label for="subject" class="form-label">Subject</label>
                      <input type="text" name="subject" id="subject" class="form-control" value="{{ request('subject') }}" placeholder="e.g., Math, English">
                    </div>

                    <div class="col-md-4">
                      <label for="keyword" class="form-label">Search Question</label>
                      <input type="text" name="keyword" id="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Search text in question">
                    </div>

                    <div class="col-md-2">
                      <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                  </form>

                <div class="table-responsive">
                  <table class="table table-bordered" id="datatablesSimple">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Grade</th>
                        <th>Question</th>
                        <th>Correct Answer</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($questions as $q)
                        <tr>
                          <td>{{ $q->id }}</td>
                          <td>{{ ucfirst($q->subject) }}</td>
                          <td>{{ $q->grade }}</td>
                          <td>{{ Str::limit($q->question, 50) }}</td>
                          <td>{{ $q->correct_answer }}</td>
                          <td>
                            <a href="{{ route('questions.edit', $q->id) }}" class="btn btn-warning btn-sm mx-1">
                              <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('questions.destroy', $q->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm mx-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

@include('admin.footer')
