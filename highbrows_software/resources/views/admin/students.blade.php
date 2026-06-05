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
                    {{-- <a class="btn my-4 text-white p-2" style="background-color: #084298"
                        href="{{ route('register.user') }}">Add New Admission</a> --}}
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header text-white" style="background-color: #084298;">
                                <h4 class="mb-0"><i class="fas fa-pencil-alt"></i> Admissions Data</h4>
                            </div>
                            @if (session('message'))
                                <div class="alert alert-success mt-3">{{ session('message') }}</div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title mb-4">Admissions List</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Passport Pic</th>
                                                <th>Student Name</th>
                                                <th>Roll No</th>
                                                <th>DOB</th>
                                                <th>Address</th>
                                                <th>Father CNIC</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($admissions as $admission)
                                                                                    <tr>
                                                                                        <td>{{ $loop->iteration }}</td>
                                                                                        <td>
                                                                                            @if ($admission->passport_pic)
                                                                                                                                            <?php 
                                                                                                                                                        $filePath = asset('storage/app/public/' . $admission->passport_pic); // Make sure the path is correct
                                                                                                $fileExtension = pathinfo($admission->passport_pic, PATHINFO_EXTENSION);
                                                                                                                                                    ?>
                                                                                                                                            @if ($fileExtension == 'pdf')
                                                                                                                                                <a href=" {{ Storage::disk('public')->url($admission->passport_pic)}}" target="_blank"
                                                                                                                                                    class="btn btn-primary">View PDF</a>
                                                                                                                                                <br>
                                                                                                                                                <embed src="{{  Storage::disk('public')->url($admission->passport_pic) }}" type="application/pdf" width="100"
                                                                                                                                                    height="100">
                                                                                                                                                <br>
                                                                                                                                                <a href="{{  Storage::disk('public')->url($admission->passport_pic)}}" download class="btn btn-primary">Download
                                                                                                                                                    PDF</a>
                                                                                                                                            @else
                                                                                                                                                <img src="{{  Storage::disk('public')->url($admission->passport_pic)}}" alt="Passport Picture" width="100"
                                                                                                                                                    height="100">
                                                                                                                                            @endif
                                                                                            @else
                                                                                                N/A
                                                                                            @endif
                                                                                        </td>


                                                                                        <td>{{ $admission->full_name }}</td>
                                                                                        <td>{{ $admission->custom_id }}</td>
                                                                                        <td>{{ $admission->dob }}</td>
                                                                                        <td>{{ $admission->postal_address }}</td>
                                                                                        <td>{{ $admission->father_cnic }}</td>
                                                                                        <td>
                                                                                            <a href="{{ route('admissions.edit', $admission->id) }}"
                                                                                                class="btn btn-warning btn-sm" title="Edit">
                                                                                                <i class="fas fa-edit"></i>
                                                                                            </a>
                                                                                            <a href="{{ route('admissions.show', $admission->id) }}"
                                                                                                class="btn btn-success btn-sm" title="View">
                                                                                                <i class="fas fa-eye"></i>
                                                                                            </a>
                                                                                            <a href="{{ route('admissions.print', $admission->id) }}"
                                                                                                class="btn btn-info btn-sm text-white" title="Print Admission Record">
                                                                                                <i class="fas fa-print"></i>
                                                                                             </a>
                                                                                             
                                                                                            <form action="{{ route('admissions.destroy', $admission->id) }}"
                                                                                                method="POST" style="display:inline;">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                                                    title="Delete" onclick="return confirm('Are you sure?')">
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
