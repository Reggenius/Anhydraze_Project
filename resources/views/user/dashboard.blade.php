@extends('layouts.layout')
<!--title of the page-->
@section('title', 'User_index')

@section('extraImports')
    <style type="text/css">
        .welcome1,
        .welcome2
        {
        text-align: center;
        font-weight: bolder;
        font-family: Georgia, Times, serif;
        font-size: 250%;
        text-shadow: 2px 2px 3px #666666;
        text-transform: uppercase;
        }
    </style>
@endsection

<!--dashboard sidepanel color-->
@section('Home_panel', "color: #fe9a0c;")
@section('content')
    @if (Session::has('calc'))
        @section('Calculator_panel', "color: #fe9a0c;")
        @include('partials.calculator')
    @endif
    <div class="welcome1">WELCOME BACK</div>
    <div class="welcome2">{{ Auth::guard('student')->user()->user_name }}</div>
@endsection