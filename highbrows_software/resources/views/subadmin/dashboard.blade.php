@include('admin.head')
<style>
    #layoutSidenav {
    display: flex;
    width: 100%;
}

#layoutSidenav .sidebar {
    width: 250px;
}

main {
    flex: 1; 
    padding: 20px;
    background-color: #f8f9fa; 
}

</style>
@include('admin.nav')

<div id="layoutSidenav" class="d-flex">
    <!-- Sidebar Section -->
    <div class="sidebar">
        @if(auth()->user()->usertype == 'admin')
        @include('admin.sidebar') 
    @elseif(auth()->user()->usertype == 'subadmin')
        @include('subadmin.sidebar') 
        @elseif(auth()->user()->usertype == 'cordinator')
        @include('cordinator.sidebar') 
    @else
    @include('student.sidebar') 
    @endif 
    </div>

    <!-- Main Content Section -->
    <main class="flex-grow-1">
        <div class="container-fluid px-4">
            <h3 class="mt-5">Welcome, {{ Auth::user()->name }}</h3>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            
            <!-- Cards Layout -->
            <div class="row">
               
                  <div class="col-md-6"><img src="{{ asset('highbroimage/people.svg') }}" alt="img"  width="100%" class="mb-3" style="border-radius: 12px"></div>
                  <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4" style="background-color: #4747A1; color: white;">
                                <div class="card-body">Classes Assigned</div>
                                <div class="card-footer">
                                   <p>@php
    $teacher = \App\Models\Teacher::where('user_id', auth()->user()->id)->with('classes')->first();
@endphp

@if($teacher && $teacher->classes->isNotEmpty())
    <ul>
        @foreach($teacher->classes as $class)
            <li>{{ $class->name }} - {{ $class->note }}</li>
        @endforeach
    </ul>
@else
    <p>No classes assigned.</p>
@endif</p>
                        
                                </div>
                            </div>
                            
                            
                        </div>
      @php
    $statusData = \App\Models\Teacher::getStatusSummaryByUserId(auth()->user()->id);
@endphp
                       <div class="col-md-6">
    <div class="card mb-4" style="background-color: #8F8EED; color: white;">
        <div class="card-body">
            <strong>Salary Status</strong>
        </div>
        <div class="card-footer">
            <div><strong>Amount:</strong>{{ number_format($statusData['salary'], 2) }} RS</div>
            <div><strong>Status:</strong> {{ ucfirst($statusData['salary_status']) }}</div>
        </div>
    </div>
</div>

                   
                        
                      
                      
                        <div class="col-md-6">
                            <div class="card mb-4" style="background-color: #F59095; color: white;">
                                <div class="card-body">Todays Attendance</div>
                                <div class="card-footer"> {{ ucfirst($statusData['attendance_today']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <!-- Terms and Conditions Modal -->
              @if(session('show_terms_modal'))
              <script>
                  document.addEventListener("DOMContentLoaded", function() {
                      var myModal = new bootstrap.Modal(document.getElementById('termsModal'), {
                          keyboard: false
                      });
                      myModal.show();
                  });
              </script>
              {{ session()->forget('show_terms_modal') }} <!-- Remove session after showing modal -->
          @endif
          
          <!-- Terms and Conditions Modal -->
          <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                          @if($terms)
                              {!! $terms->rules !!}
                          @else
                              <p>No terms and conditions available.</p>
                          @endif
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Agree</button>
                      </div>
                  </div>
              </div>
          </div>
          

        </div>
    </main>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('termsModal'), {
            keyboard: false
        });
        myModal.show(); 
    });
    </script>
    
@include('admin.footer')
