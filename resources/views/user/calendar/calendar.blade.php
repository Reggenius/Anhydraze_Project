@extends('layouts.layout')
<!--title of the page-->
@section('title', 'User_calendar')
<!--css importation starts-->
@section('extraImports')
    <!--Calendar styling-->
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}" type="text/css"  />
@endsection
<!--css importation ends-->
<!--dashboard sidepanel color-->
@section('Calendar_panel', "color: #fe9a0c;")
@section('content')

    @if (Session::has('calc'))
        @section('Calculator_panel', "color: #fe9a0c;")
        @include('partials.calculator')
    @endif

    @php
        $link = 'none';
    @endphp

    @if ($resource != null && count($resource)>0)
        @php
            $elementCount = 0;
        @endphp
        
        @foreach ($resource as $item)
            @if($loop->first)
                <table>
                <tr>    
            @endif
            
            <td>
                <div class="calendar_details">
                    @php
                        $data = [
                            $id,
                            $scheduleId = $item->resource_id,
                            $link
                            ];
                    @endphp
                    <a href="{{ route('user.calendar.details',  $data) }}" ><input type="submit" value= "{{ $item->event_date }}" id="submit" /></a>
                </div>
            </td>
            @php
                $elementCount++;
            @endphp
            
            @if($elementCount%3 == 0)
                </tr>
                <tr>
            @endif
            @if($loop->last)
                    </tr>
                </table>
                <form id="formcal" action="{{ route('user.calendar.schedule', $id) }}"> 
                    <p>No Scheldules yet!</p>
                    <button id="calendarButton">
                        <span id="calendarSpan">Add a Schedule</span>
                    </button>
                </form>
            @endif
        @endforeach
    @else
        <form id="formcal" action="{{ route('user.calendar.schedule', $id) }}"> 
            <p>No Scheldules yet!</p>
            <button id="calendarButton">
                <span id="calendarSpan">Add a Schedule</span>
            </button>
        </form>
    @endif

@endsection