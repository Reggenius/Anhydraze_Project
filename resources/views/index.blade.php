@section('title', 'Index')
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
         <a href="{{ route('pages.home') }}" style="color: #fe9a0c;">Home</a>
         <a href="{{ route('user.login') }}">Login</a>
         <a href="{{ route('pages.signup') }}">SignUp</a>
         <a href="#aboutId">About</a>
         <a href="#contactId">Contact</a>
      </div>
      <!-- end sidepanel -->

      <!-- start header -->
      <header>
         <!-- header inner -->
         <div class="head-top" id="homeId">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-sm-3">
                     <div class="logo">
                        <!-- INSERTION BEGINS Line40-->
                        <a href="dashboard.html"><img src="{{ asset('images/logo_footer.png') }}" /></a>
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

      <!-- start slider section -->
      <div class=" banner_main">
         <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
               <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
               <li data-target="#myCarousel" data-slide-to="1"></li>
               <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
               <div class="carousel-item active">
                  <div class="container">
                     <div class="carousel-caption relative">
                        <div class="bg_white">
                           <h1>Welcome To <span class="yello">ScholarPal</span></h1>
                           <p>The More that you read, the more things you will know, the more that you learn, the more places you'll go. <br/><i>- Dr. Seuss</i></p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="container">
                     <div class="carousel-caption relative">
                        <div class="bg_white">
                           <h1>Welcome To <span class="yello">ScholarPal</span></h1>
                           <p>"Tell me and I forget. Teach me and I remember. Involve me and I learn." <br/><i>- Benjamin Franklin</i></p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="container">
                     <div class="carousel-caption relative">
                        <div class="bg_white">
                           <h1>Welcome To <span class="yello">ScholarPal</span></h1>
                           <p>"Take the attitude of a student, never be too big to ask questions, never know too much to learn something new." <br/><i>- Og Mandino</i></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
            </a>
         </div>
      </div>
      <!-- end slider section -->


      <!-- start content section -->
      <!-- six_box-->
      <div id="about" class="about top_layer">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="titlepage">
                     <h2 id="aboutId">About us</h2>
                     <p>We Make Student Life Easier and Better</p>
                  </div>
               </div>
               <div class=" col-sm-12">
                  <div class="about_box">
                     <div class="row d_flex">
                        <div class="col-md-5">
                           <div class="about_box_text">
                              <h3>student-friendly Interface</h3>
                              <p>We Help students in organising 
                                 their day-to-day student activities
                                 so as to lessen their stress and 
                                 manage their time effectively
                              </p>
                           </div>
                        </div>
                        <div class=" col-md-7  pppp">
                           <div class="about_box_img">
                              <figure><img src="{{ asset('images/about_img.png') }}" alt="#" /></figure>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end six_box-->

      <!-- building -->
      <div class="building">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>EDUCATION IS NOT THE LEARNING OF FACTS BUT <br><span class="yello">THE TRAINING OF THE MIND <br></span>TO THINK<br><i>...ALBERT EINSTEIN</i></h2>
                     {{--<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, asIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, asIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as</p>--}}
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end building -->

      <!-- services -->
      <div class="services_main">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Services</h2>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-12">
                  <div class="tab-content card back_bg" id="myTabContentMD">
                     <div class="tab-pane fade show active" id="home-md" role="tabpanel" aria-labelledby="home-tab-md">
                        <div class="row">
                           <div class="col-md-4 col-sm-6 padding_0 margin_right20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img1.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Student-Time Management</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 col-sm-6 padding_0 margin_top70p margin_right20 margin_left20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img2.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Timetable Organisation</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 col-sm-6 padding_0 margin_left20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img3.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Project Management</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 offset-md-8 col-sm-6 padding_0 margin_top170">
                              <div class="services margin_left60">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img4.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Result Organisation</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade" id="profile-md" role="tabpanel" aria-labelledby="profile-tab-md">
                        <div class="row">
                           <div class="col-md-4 col-sm-6 padding_0 margin_right20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img3.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Project Management</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 col-sm-6 padding_0 margin_top70p margin_right20 margin_left20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img2.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Timetable Organisation</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 col-sm-6 padding_0 margin_left20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img4.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Result Organisation</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 offset-md-8 col-sm-6 padding_0 margin_top170">
                              <div class="services margin_left60">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img1.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Student-Time Management</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade" id="contact-md" role="tabpanel" aria-labelledby="contact-tab-md">
                        <div class="row">
                           <div class="col-md-4 col-sm-6 padding_0 margin_right20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img4.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Result Organisation</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 col-sm-6 padding_0 margin_top70p margin_right20 margin_left20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img2.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Timetable Organisation</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 col-sm-6 padding_0 margin_left20">
                              <div class="services">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img1.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Student-Time Management</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 offset-md-8 col-sm-6 padding_0 margin_top170">
                              <div class="services margin_left60">
                                 <div class="services_img">
                                    <figure><img src="{{ asset('images/service_img3.png') }}" alt="#" />  </figure>
                                    <div class="ho_dist">
                                       <span>Project Management</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end services -->

      <!-- instant -->
      <div class="instant">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-6">
                  <div class="titlepage text_align_left">
                     <h2>Get Started!</h2>
                     <a class="read_more" href="{{ route('pages.signup') }}">Sign-Up</a>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="instant_img">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end instant -->
      <!-- end content section -->


        <!-- start footer -->
        @section('footer')
        <footer>
                <div class="footer">
                    <div class="container">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <ul class="social_icon text_align_center">
                                <li> <a href="javascript:void(0)"><i class="fa fa-facebook-f"></i></a></li>
                                <li> <a href="javascript:void(0)"><i class="fa fa-twitter"></i></a></li>
                                <li> <a href="javascript:void(0)"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
                                <li> <a href="javascript:void(0)"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                        
                        <!---->
                        <div class="col-md-4 col-sm-6">
                            <div class="reader">
                            <a id="test4" href ="{{ route('pages.home') }}"><img  src="{{ asset('images/logo_footer.png') }}" /></a>
                                <p class="padd_flet40"><i>"...Making School Life Easier for You!"</i></p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="reader">
                                <h3>Explore</h3>
                                <ul class="xple_menu">
                                <li><a href="#homeId">Home</a></li>
                                <li><a href="#aboutId">About</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="reader">
                                <h3>Recent Posts</h3>
                                <ul class="re_post">
                                <li><img src="{{ asset('images/re_img1.jpg') }}" alt="#"/></li>
                                <li><img src="{{ asset('images/re_img2.jpg') }}" alt="#"/></li>
                                <li><img src="{{ asset('images/re_img3.jpg') }}" alt="#"/></li>
                                <li><img src="{{ asset('images/re_img4.jpg') }}" alt="#"/></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="reader">
                                <h3 id="contactId">Contact Us</h3>
                                <p>07033073233 <br>scholarpalweb@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="copyright text_align_center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <p>Copyright &copy; 2022 &mdash; ScholarPal</p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </footer>
        @endsection
        @include('partials.footer')
        <!-- end of footer -->
