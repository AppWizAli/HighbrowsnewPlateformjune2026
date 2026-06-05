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
            @include('admin.main') 
        </div>
        @include('admin.footer') 
