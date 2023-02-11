@extends('layouts.layout')
<!--title of the page-->
@section('title', 'User_calendar')
<!--css importation starts-->
@section('extraImports')
    <!--calendar schedule styling styling-->
    <link rel="stylesheet" href="{{ asset('css/schedule_calendar.css') }}" type="text/css"  />
@endsection
<!--css importation ends-->
<!--dashboard sidepanel color-->
@section('Calendar_panel', "color: #fe9a0c;")
@section('content')
    @if (Session::has('calc'))
        @section('Calculator_panel', "color: #fe9a0c;")
        @include('partials.calculator')
    @endif
    <form id="formId" action="{{ route('user.calendar.upload', $id) }}" method="POST">
        @csrf
        <label>
            Event:
            <input type="text" name="event_name" maxlength="100" placeholder="Lecture, lunch time with friends e.t.c." required/>
        </label>
        <br />
        <label id="mail">
            Date:
            <input type="date" name="event_date" required/>
        </label>
        <br />
        <label id="mail">
            Time:
            <input type="time" name="event_time" required/>
        </label>
        <br />
        <label>
            Address:<br/>
            <textarea id="describe" name="event_description" placeholder="e.g., RM 220 FPSLT Lecture Hall" maxlength="100" required></textarea>
        </label>
        <div class="calendarEvents">
            <input type="submit" value="Scheldule" id="submit" />
        </div>
            <p>Already have an existing scheldule? <a href ="{{ route('user.calendar', $id) }}">View</a></p>
    </form>
    <!-- form ends -->

@endsection