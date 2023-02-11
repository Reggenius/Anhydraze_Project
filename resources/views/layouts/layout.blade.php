<!--css importation starts-->
@section('headerImports')
    <!-- Session Styling -->
    <style type="text/css">
        .success,
        .error
        {
            background-color: maroon; 
            color: white;  
            width: fit-content; 
            padding: 4px; 
            font-style: italic; 
            font-weight: bold;
        }
    </style>
    <!-- Session Styling ends -->
    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- style css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- responsive-->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <!-- calculator styling-->
    <link rel="stylesheet" href="{{ asset('css/calc.css') }}">
    <!-- awesome fontfamily -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @yield("extraImports")
@endsection
<!--css importation ends--> 
@include('partials.header')

    <!-- body -->
    <body class="main-layout">
        <!-- loader  -->
        @include('partials.preload')
        <!-- end loader -->

        <!-- start sidepanel -->
        <div id="mySidepanel" class="sidepanel"><!--In style.css line 295-->
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <a href="{{ route('user.index', $id) }}" style="@yield('Home_panel')">Home</a>
            <a href="{{ route('user.calendar', $id) }}" style="@yield('Calendar_panel')">Calendar</a>
            <a href="{{ route('user.timetable', $id) }}" style="@yield('Timetable_panel')">Timetable</a>
            <a href="{{ route('user.notes', $id) }}" style="@yield('Notes_panel')">Notes</a>
            <a href="{{ route('user.calc', $id) }}" style="@yield('Calculator_panel')">Calculator</a>
            <a href="{{ route('pages.session') }}">Settings</a>
            <a href="{{ route('user.logout', $id) }}">Logout</a>
        </div>
        <!-- end sidepanel -->

        <!-- start header -->
        <header>
            <div class="head-top">
                <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="logo">
                            <a  id="test1" href="{{ route('user.index', $id) }}"><img  src="{{ asset('images/logo_footer.png') }}" /></a>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <ul class="email text_align_right">
                            <a href="{{ route('pages.session') }}"><li class="d_none fa fa-user"><i class="fa fa-caret-down" aria-hidden="true"></i></li></a>
                            <li> <button class="openbtn" onclick="openNav()"><img src="{{ asset('images/menu_btn.png') }}"></button></li>
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        </header>
        <!-- end header fa-user-->

        @include('partials.session')

        @yield("content")

      
        <!-- start footer -->
        @section('footer')
        <footer style=" margin-top: 80%; background-color: rgb(129, 14, 14);">
            <span >
            <div class="copyright text_align_center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                        <div>
                        <p>Copyright &copy; 2022 &mdash; ScholarPal</p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            </span>
        </footer>
        @endsection
        @include('partials.footer')
        <!-- end of footer -->
