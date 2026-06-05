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
                <div class="col-md-12">
                  <div class="col-md-6"><img src="{{ asset('highbroimage/people.svg') }}" alt="img"  width="100%" class="mb-3" style="border-radius: 12px"></div>
                </div>
              </div>
        </div>
    </main>
</div>

@include('admin.footer')
