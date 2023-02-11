@extends('layouts.layout')
<!--title of the page-->
@section('title', 'User_timetable')
<!--timetable sidepanel color-->
@section('Timetable_panel', "color: #fe9a0c;")
<!--css importation starts-->
@section('extraImports')
    <!--additional form styling-->
    <link rel="stylesheet" href="{{ asset('css/details_timetable.css') }}" type="text/css"  />
@endsection
<!--css importation ends--> 
<!-- start content -->
@section('content')
    @if (Session::has('calc'))
        @section('Calculator_panel', "color: #fe9a0c;")
        @include('partials.calculator')
    @endif

    <form id="timetable_form"  action="{{ route('user.timetable.edit', $id) }}" method="POST" >
        @csrf
        <img src="{{ asset($resource->img_url) }}" alt="" width="100%" height="100%">
        <div class="img_table_div">
            <div class="tabledescribe">Description:</div>
            <textarea id="describe" name="img_description" placeholder="Optional" maxlength="20"  autofocus>{{ $resource->img_description }}</textarea>
            <div class="timetableEvents">
                <input type="submit" value="Save" id="submit" />
            </div>
        </div>
    </form>
    <div class="timetableDel">
        <a href="{{ asset($resource->img_url) }}" download><input type="submit" value="Download" id="submit2" /></a>
        <a href="{{ route('user.timetable.delete', $id) }}" ><input type="submit" value="Delete" id="submit2" /></a>
    </div>
    <hr>
@endsection
<!-- end of content -->