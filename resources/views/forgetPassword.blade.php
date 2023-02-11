<!--title of the page-->
@section('title', 'Forgot_Password')

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
        <form id="formId" action="{{ route('pages.password.email') }}" method="post" style="max-height: 100px; min-height: 125px;"><!--Insert Regex of no space characters-->
            @csrf
            @include('partials.session')
            <label id="mail">
                Email:
                <input type="email" name="email" placeholder="scholarpal@gmail.com" autofocus required/>
            </label>
            <br />
            <div>
                <input type="submit" value="Reset Password" id="submit" style="width: 40%; margin-left: 63%;"/>
            </div>
        </form>
        <!-- form ends -->
    </body>
</html>