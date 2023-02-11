<!--title of the page-->
@section('title', 'Login')

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
        <form id="formId" action="{{ route('user.validLogin') }}" method="POST">    <!--Insert Regex of no space characters-->
            @csrf
            @include('partials.session')
            <label>
                Username:
                <input type="text" name="user_name" placeholder="e.g. scholarpal" maxlength="50" autofocus required/>
            </label>
            <br />
            <label>
                Password:
                <input type="password" name="password" maxlength="30" required/>
                <p class="one">
                    <a href ="{{ route('pages.session') }}">forgot Password</a>
                </p>
                <br/>
            </label>
            <div>
                <input type="submit" value="Login" id="submit" />
            </div>
            <br />
            <p >Don't have an account? 
                <a href ="{{ route('pages.signup') }}">SignUp</a>
            </p>
        </form>
        <!-- form ends -->
    </body>
</html>