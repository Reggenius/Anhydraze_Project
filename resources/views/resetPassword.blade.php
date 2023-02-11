<!--title of the page-->
@section('title', 'Reset_Password')

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
        <form id="formId" action="{{ route('pages.password.update') }}" method="post"><!--Insert Regex of no space characters-->
            @csrf
            @include('partials.session')
            <label id="mail">
                Email:
                <input type="email" name="signmail" placeholder="scholarpal@gmail.com" required/>
            </label>
            <br />
            <label>
                Password:
                <input type="password" name="password" placeholder="Must consist of symbols, numbers and alphabets" maxlength="30" required/>
                <br/>
            </label>
            <label>
                Confirm Password:
                <input type="password" name="password" placeholder="Re-enter password" maxlength="30" required/>
                <br/>
            </label>
            <div>
                <input type="submit" value="Reset" id="submit" />
            </div>
        </form>
        <!-- form ends -->
    </body>
</html>