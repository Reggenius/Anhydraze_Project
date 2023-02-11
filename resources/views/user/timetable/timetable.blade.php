@extends('layouts.layout')
<!--title of the page-->
@section('title', 'User_timetable')
<!--dashboard sidepanel color-->
@section('Timetable_panel', "color: #fe9a0c;")
<!--css importation starts-->
@section('extraImports')
    <!--additional form styling-->
    <link rel="stylesheet" href="{{ asset('css/timetableUpload.css') }}" type="text/css"  />
@endsection
<!--css importation ends--> 
<!-- start content -->
@section('content')
    @if (Session::has('calc'))
        @section('Calculator_panel', "color: #fe9a0c;")
        @include('partials.calculator')
    @endif

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
                            $resourceId = $item->resource_id
                            ];
                    @endphp
                    <a href="{{ route('user.timetable.details',  $data) }}" ><input type="submit" value= "{{ $item->img_description }}" id="submit" /></a>
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
                <div class="timetableContainer">
                    <form id="formId" action="{{ route('user.timetable.upload', $id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <!--Upload new Image (timetable)-->
                        <div class="UploadTimetable">
                            <h1>Upload Timetable <br/>
                                <small>(png, jpg, jpeg) Max file size is 2MB</small>
                            </h1>
                            <div class="avatar-upload">
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background: url(C:/wamp64/www/BACKEND_DEV/Project/Anhydraze/Media/Pictures/785668.png) no-repeat center;"></div>
                                </div>
                            </div>
                            <div class="avatar-edit" style="text-align: center;">
                                <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" style="width: fit-content;" required/> 
                            </div>
                            <div>
                                Description:<br/>
                                <textarea id="describe" name="img_description" placeholder="e.g Second Semester" maxlength="20" required></textarea>
                            </div>
                            <div>
                                <div id="uploaddiv" style="max-width: 538px;">
                                    <input type="submit" value="Upload" id="upload" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endforeach

    @else
        <div class="timetableContainer">
            <form id="formId" action="{{ route('user.timetable.upload', $id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <!--Upload new Image (timetable)-->
                <div class="UploadTimetable">
                    <h1>Upload Timetable <br/>
                        <small>(png, jpg, jpeg) Max file size is 2MB</small>
                    </h1>
                    <div class="avatar-upload">
                        <div class="avatar-preview">
                            <div id="imagePreview" style="background: url(C:/wamp64/www/BACKEND_DEV/Project/Anhydraze/Media/Pictures/785668.png) no-repeat center;"></div>
                        </div>
                    </div>
                    <div class="avatar-edit" style="text-align: center;">
                        <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" style="width: fit-content;" required/> 
                    </div>
                    <div>
                        Description:<br/>
                        <textarea id="describe" name="img_description" placeholder="e.g Second Semester" maxlength="20" required></textarea>
                    </div>
                    <div>
                        <div id="uploaddiv" style="max-width: 538px;">
                            <input type="submit" value="Upload" id="upload" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
    
@endsection
<!-- end of content -->

<!-- start javascript imports -->
@section('footerImports')
    <script src="{{ asset('js/timetableUpload.js') }}"></script>
 @endsection
<!-- end of footer imports -->