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
            <div class="container">
                <div class="d-flex justify-content-start">
                    <a class="btn my-4 text-white p-2" style="background-color: #084298" href="{{ route('subadmin.create') }}">Add New Teacher</a>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Teachers</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Teachers List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="examsTable">
                                        <thead>
                                          <tr>
                                            <th>#</th>
                                            <th>Jioning</th>

                                            <th>Profile Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            {{-- <th>Status</th> --}}

                                            <th><i class="fa fa-ellipsis-h"></i></th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <!-- Example Row, you can dynamically generate rows using Blade templates -->
                                          @php
                                              $count=0;
                                          @endphp
                                          @foreach ($employees as $employee)
                                          <tr>
                                            <td>{{ ++$count }}</td>
                                            <td>{{ $employee->joining_date }}</td>

                                            <td><img src=" {{ Storage::disk('public')->url($employee->image)}}" alt="Employee Image" style="width: 25px; height: auto;margin-right:4px"></td>
                                            <td>{{$employee->name  }}</td>
                                            <td>{{$employee->email }}</td>

                                            {{-- <td><a class='btn btn-sm btn-success '>{{ $employee->status }}</a></td> --}}

                                            <td>
                                              <!-- View Button -->
                                              <a href="{{ route('teachers.show', $employee->id ) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                              </a>

                                              <!-- Edit Button -->
                                              <a href="{{ route('teachers.edit',  $employee->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                              </a>

                                              <!-- Delete Button -->
                                              <form id="delete-form-{{ $employee->id }}"
                                                action="{{ route('teachers.destroy', $employee->id) }}"
                                                method="POST"
                                                style="display:inline;">
                                              @csrf
                                              @method('DELETE')
                                              <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                                      onclick="confirmDelete({{ $employee->id }})">
                                                  <i class="fas fa-trash"></i>
                                              </button>
                                          </form>


                                            </td>
                                          </tr>
                                          @endforeach

                                          <!-- Add more rows here -->
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
<script>
    function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this teacher?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>


@include('admin.footer')
