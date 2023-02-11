@extends('layouts.layout')
<!--title of the page-->
@section('title', 'User_notes')
<!--dashboard sidepanel color-->
@section('Notes_panel', "color: #fe9a0c;")
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
                            $noteId = $item->resource_id
                            ];
                    @endphp
                    <a href="{{ route('user.notes.details',  $data) }}" ><input type="submit" value= "{{ $item->note_description }}" id="submit" /></a>
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
                <div class="timetableContainer1">
                    <form id="formId" action="{{ route('user.notes.upload', $id) }}" method="post" enctype="multipart/form-data">
                        <!--Upload new Image (timetable)-->
                        @csrf
                        <div class="UploadTimetable">
                            <h1 id="h1">Upload Note <br/>
                                <small id="small">(.pdf, .doc, .docx, .txt) Max file size is 2MB</small>
                            </h1>

                            <div class="avatar-edit" style="text-align: center;">
                                <input type='file' id="imageUpload"  name="document" accept=".pdf, .doc, .docx, .txt" style="width: fit-content;" required/> 
                            </div>
                            <div class="describeDiv">
                                Description:<br/>
                                <textarea id="describe" name="note_description" placeholder="e.g COS 204" maxlength="20" required></textarea>
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
        <div class="timetableContainer1">
                <form id="formId" action="{{ route('user.notes.upload', $id) }}" method="post" enctype="multipart/form-data">
                    <!--Upload new Image (timetable)-->
                    @csrf
                    <div class="UploadTimetable">
                        <h1 id="h1">Upload Note <br/>
                            <small id="small">(.pdf, .doc, .docx, .txt) Max file size is 2MB</small>
                        </h1>

                        <div class="avatar-edit" style="text-align: center;">
                            <input type='file' id="imageUpload"  name="document" accept=".pdf, .doc, .docx, .txt" style="width: fit-content;" required/> 
                        </div>
                        <div class="describeDiv">
                            Description:<br/>
                            <textarea id="describe" name="note_description" placeholder="e.g COS 204" maxlength="20" required></textarea>
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