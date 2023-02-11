<!--title of the page-->
@section('title', 'SignUp')

<!--css importation starts-->
@section('headerImports')
    <link href="{{ asset('css/Login_Signup.css') }}" type="text/css" rel="stylesheet" />
@endsection
<!--css importation ends-->
@include('partials.header')

<body>
    <!-- logo starts -->
    <p ><a id="test4" href ="{{ route('pages.home') }}"><img  src="{{ asset('images/logo_footer.png') }}" /></a></p>
       <!-- logo ends -->
        <!-- form starts -->
       <form id="formId" action="{{ route('user.add') }}" method="POST">    <!--Insert Regex of no space characters-->
            @csrf
            @include('partials.session')
                <label>
                    Username:
                    <input type="text" name="user_name"  maxlength="20" autofocus required/>
                </label>
                <br />
                <label id="mail">
                    Email:
                    <input type="email" name="user_email" placeholder="e.g. scholarpalweb@gmail.com" required/>
                </label>
                <br />
                <label>
                    Password:
                    <input type="password" name="password" placeholder="must contain letters and numbers." required/><br/>
                </label>
                <div>
                    <input type="submit" value="Sign-Up" id="submit" />
                </div>
                   <p>Already have an account? <a href ="{{ route('user.login') }}">Login</a></p>
        </form>
        <!-- form ends -->
    </body>
</html>