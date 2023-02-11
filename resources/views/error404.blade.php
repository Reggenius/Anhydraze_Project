<!--title of the page-->
@section('title', 'Error404')

<!--css importation starts-->
@section('headerImports')
    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- style css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- responsive-->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <!-- awesome fontfamily -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
         <a href="javascript:void(0)" style="color: #fe9a0c;">Error404</a>
         <a href="{{ route('pages.home') }}">Home</a>
      </div>
      <!-- end sidepanel -->

      <!-- start header -->
      <header>
         <!-- header inner -->
         <div class="head-top">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-sm-3">
                     <div class="logo">
                        <!-- INSERTION BEGINS Line40-->
                        <a href="{{ route('pages.home') }}"><img src="{{ asset('images/logo_footer.png') }}" /></a>
                        <!-- INSERTION ENDS -->
                     </div>
                  </div>
                  <div class="col-sm-9">
                     <ul class="email text_align_right">
                        <li class="d_none"> <a href="{{ route('pages.signup') }}">SignUp<i class="fa fa-user" aria-hidden="true"></i></a> </li>
                        <li class="d_none"> <a href="{{ route('user.login') }}">Login <i class="fa fa-user" aria-hidden="true"></i></a> </li>
                        <li> <button class="openbtn" onclick="openNav()"><img src="{{ asset('images/menu_btn.png') }}"></button></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <!-- end header -->

      <!-- display error message -->
        <div class=" banner_main">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="container">
                            <div class="carousel-caption relative">
                                <div class="bg_white">
                                    <h1>Oops! Page <span class="yello">NOT </span>Found</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <!-- end error message -->

        <!-- start footer -->
        @section('footer')
            <footer style="background-color: rgb(129, 14, 14);">
                <span >
                <div class="copyright text_align_center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div>
                                    <p>Copyright 2022 All Right Reserved</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </span>
            </footer>
        @endsection
        @include('partials.footer')
        <!-- end of footer -->