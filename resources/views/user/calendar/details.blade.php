@extends('layouts.layout')
<!--title of the page-->
@section('title', 'User_timetable')
<!--dashboard sidepanel color-->
@section('Calendar_panel', "color: #fe9a0c;")
<!--css importation starts-->
@section('extraImports')
    <!--Calendar styling-->
    <link rel="stylesheet" href="{{ asset('css/details_calendar.css') }}" type="text/css"  />
@endsection
<!--css importation ends--> 
<!-- start content -->
@section('content')

    @if (count($resource)>0)
        @php
            $event_name;
            $event_date;
            $event_time;
            $event_description;
            if($linkData != null)
            {
                $event_name = $linkData->event_name;
                $event_date = $linkData->event_date;
                $event_time = $linkData->event_time;
                $event_description = $linkData->event_description;
            }

            $link = null;
        @endphp
        
        @foreach ($resource as $item)
            @if($loop->first)
                @php
                    $current = null;

                    $times = strtotime($item->event_time);
                    $convert = date("h:i a", $times );

                    $dates = strtotime($item->event_date);
                    $dateCon = date("d", $dates );
                    $monthCon = date("M", $dates );
                    $yearCon = date("Y", $dates );

                    $currentDate = date("Y:M:d", time());
                    $scheduledDate = date("Y:M:d", $dates);

                    if($currentDate == $scheduledDate)
                        $current = "Today";
                    else
                        $current;

                @endphp  

                <!-- partial:index.partial.html -->
                <div class="container5">
                    <div class="calendar dark">
                        <div class="calendar_header">
                            <h1 class = "header_title">Welcome Back!</h1>
                            <p class="header_copy"> Calendar Plan</p>
                        </div>
                        <div class="calendar_plan">
                            <div class="cl_plan">
                                <div class="cl_title">{{ $current }}</div>
                                <div class="cl_copy">{{ $dateCon }}  {{ $monthCon }}  {{ $yearCon }}</div>
                                <a href="calendar.html">
                                    <div class="cl_add">
                                    Add <i class="fas fa-plus"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="calendar_events">
                            <p class="ce_title">Upcoming Events</p>
                                @php
                                if($linkData == null)
                                {
                                    $event_name = $item->event_name;
                                    $event_date = $item->event_date;
                                    $event_time = $item->event_time;
                                    $event_description = $item->event_description;
                                    session()->put('scheduleEditId', $item->resource_id);
                                }
                                    
                                    $data = [
                                        $id,
                                        $scheduleId = $item->resource_id,
                                        $link = 'click'
                                        ];
                                @endphp
                                
                            <a href="{{ route('user.calendar.details',  $data) }}#details">
                                <div class="event_item" style="color: gray;">
                                    <div class="ei_Dot dot_active"></div>
                                    <div class="ei_Title">{{ $convert }}</div>
                                    <div class="ei_Copy" >{{ $item->event_name }}</div>
                                </div>
                            </a>
                @else
                    @php
                        $times = strtotime($item->event_time);
                        $convert = date("h:i a", $times );

                        $data = [
                            $id,
                            $scheduleId = $item->resource_id,
                            $link = 'click'
                            ];
                    @endphp
                    <a href="{{ route('user.calendar.details',  $data) }}#details" >
                        <div class="event_item" style="color: gray;">
                            <div class="ei_Dot"></div>
                            <div class="ei_Title">{{ $convert }}</div>
                            <div class="ei_Copy">{{ $item->event_name }}</div>
                        </div>
                    </a>
            @endif
        @endforeach
                        <div>
                            <a href ="{{ route('user.calendar.destroy', $id) }}">
                                <input type="submit" value="Delete" id="submit" />
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- white background -->
                <div class="calendar light">
                    <!-- form starts -->
                    <span id="details">
                        <form id="formId" action="{{ route('user.calendar.edit', $id) }}" method="POST">    <!--Insert Regex of no space characters-->
                            @csrf
                            <div>
                                Event:
                                <input id="eventId" type="text" name="event_name" maxlength="100" placeholder="Lecture, lunch time with friends e.t.c." value="{{ $event_name }}" required/>
                            </div>
                            <label id="mail">
                                Date:
                                <input type="date" name="event_date" value="{{ $event_date }}" required/>
                            </label>
                            <br />
                            <!--Check whether there's special input type for email in html otherwise use Regex-->
                            <label id="mail">
                                Time:
                                <input type="time" name="event_time" value="{{ $event_time }}" required/>
                            </label>
                            <br />
                            <div>
                                Address:
                                <textarea id="describe" name="event_description" placeholder="e.g., RM 220 FPSLT Lecture Hall" maxlength="100" required>{{ $event_description }}</textarea>
                            </div>
                            <div class="calendarEvents">
                                <input type="submit" value="Save" id="submit" />
                            </div>
                            <p>Scheldule already expired? <a href ="{{ route('user.calendar.delete', $id) }}">Delete</a></p>
                        </form>
                        <!-- form ends -->
                    </span>
                </div>
            </div>
        <!-- partial -->
    @else
        @php
            return redirect()->route('user.calendar', $id); 
        @endphp
    @endif

@endsection
<!-- end of content -->

<!-- start javascript imports -->
@section('footerImports')
    <script src="{{ asset('js/timetableUpload.js') }}"></script>
 @endsection
<!-- end of footer imports -->