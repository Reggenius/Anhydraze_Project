<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\UserResource;

class StudentUserController extends Controller
{
    /**
     * Display the user index.
     * 
     * @param  int $userId
     * @return \Illuminate\Http\Response(dashboard.blade.php)
     */ 
    public function dashboard(int $userId)
    {
        $data = Student::find($userId);
        $userData = [
            'id' => $userId,
            'resource' => $data
        ];
        return view('user/dashboard', $userData);
    }

    /**
     * Display calculator.
     * @param  int $userId
     * @return \Illuminate\Http\Response(previous page)
     */ 
    public function calculator(int $userId)
    {
        if(Session::has('calc'))
            session()->forget('calc');
        else
            session()->put('calc', 'clicked');

        return back();
    }


/*----------------------------Notes Functions Starts Here----------------------------------------*/

    /**
     * Display user's note(s).
     * @param  int $userId
     * @return \Illuminate\Http\Response(notes.blade.php)
     */ 
    public function userNotes(int $userId)
    {
        //Retrieve user notes if any
        $data = UserResource::where('student_id', $userId)
                                ->select('note_description', 'resource_id')
                                ->where('note_description', '!=', 'NULL')
                                ->orderBy('note_description', 'asc')
                                ->get();
        $userData = [
            'id' => $userId,
            'resource' => $data
        ];
        return view('user/notes/notes', $userData);
    }

    /**
     * Store a newly uploaded note in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param int $userId
     * @return \Illuminate\Http\Response(notes.blade.php)
     */
    public function storeNotes(Request $request, int $userId)
    {
        //Start Image Processing
        $mainFile = $request->file('document');
        $docName = $mainFile->getClientOriginalName();
        $docExtension = $mainFile->getClientOriginalExtension();
        $getSize = $mainFile->getSize();
        if($docExtension == "png" || $docExtension == "jpeg" || $docExtension == "jpg" || $docExtension == "pdf"
            || $docExtension == "doc" || $docExtension == "docx" || $docExtension == "txt")
        {
            if($getSize <= 5242880)
            {
                //Validate the description
                $validate = Validator::make($request->all(), [
                    'note_description'=>'required|string|unique:user_resources'
                    ]);
                if($validate->fails())
                {
                    session()->put('error', $validate->errors()->first());
                    return redirect()->route('user.notes', $userId);
                }
                $validInput = $validate->validated();

                $ran = uniqid("doc_");   //timestamp to be used in renaming file
                $newDocName =  $ran.'.'.$docExtension;  //Create new file name for uploaded file
                $docUrl = "storage/notes/{$newDocName}"; //path to document
                $mainFile->storeAs('/notes', $newDocName, 'public');

                
    
                //save resource in storage
                $data = UserResource::where('user_resources.student_id', $userId)
                                        ->select('resource_id', 'note_url', 'note_description')
                                        ->where('note_description')
                                        ->orwhere('note_url')
                                        ->get();
                //Check if there is any existing row- column space for storage
                if(count($data) > 0)
                {
                    $extract = $data->first();
                    $newNoteId = $extract->resource_id;
                    //Insert resource into an already existing row in database
                    $newNote = UserResource::find($newNoteId);
                    $newNote->note_url = $docUrl;
                    $newNote->note_description = $validInput['note_description'];
                }   
                else
                //Create a new one
                {
                    $newNote = new UserResource;
                    $newNote->student_id = $userId;
                    $newNote->note_url = $docUrl;
                    $newNote->note_description = $validInput['note_description'];
                }
    
                //Save resource
                if($newNote->save())        
                {
                    session()->put('success', 'File uploaded successfully!.');
                    return redirect()->route('user.notes', $userId);
                }
                else
                {
                    session()->put('error', 'File not uploaded, try again.');
                    return redirect()->route('user.notes', $userId);
                }   
            }
            else    //if file exceeds limit
            {
                session()->put('error', 'File size limit exceeded.');
                return redirect()->route('user.notes', $userId);
            }
        }
        else    //if file is not of the required type
        {
            session()->put('error', 'File is not of the required type.');
            return redirect()->route('user.notes', $userId);
        }
    }

    /**
     * Display a view of the user note.
     * @param  int $userId
     * @param  int $noteId
     * @return \Illuminate\Http\Response(details.blade.php)
     */
    public function viewNote(int $userId, int $noteId)
    {
        //$noteId for use in update and deletion
        session()->put('noteId', $noteId);
        
        $data = UserResource::where('resource_id', $noteId)
                                ->select('note_description', 'note_url', 'resource_id')
                                ->where('note_url', !'NULL')
                                ->first();
        $userData = [
            'resource' => $data,
            'id' => $userId
        ];
        return view('user/notes/details', $userData);
    }

    /**
     * Update user_note file description
     * @param  Request $request
     * @param  int $userId
     * @return \Illuminate\Http\Response(details.blade.php)
     */
    public function editNote(Request $request, int $userId)
    {
        if(Session::has('noteId'))
        {
            $noteId = session()->get('noteId');

            //Validate the description
            $validate = Validator::make($request->all(), [
                        'note_description'=>'required|string|unique:user_resources'
                        ]);
            
            if($validate->fails())
            {
                session()->put('error', $validate->errors()->first());
                return redirect()->route('user.notes.details', [$userId, $noteId]);
            }
            $validInput = $validate->validated();

            $description = UserResource::find($noteId);
            if($description == null || $description->note_description == null)
            {
                session()->put('error', 'Note does not exist.');
                return redirect()->route('user.notes', $userId);
            }
            $description->note_description = $validInput['note_description'];

            if($description->save())
            {
                session()->put('success', 'Description updated successfully!.');
                return redirect()->route('user.notes.details', [$userId, $noteId]);
            }
            else
            {
                session()->put('error', 'Description not updated, try again.');
                return redirect()->route('user.notes.details', [$userId, $noteId]);
            }
        }
        else
        //If resourceId was not stored as a session
        {
            return redirect()->route('user.notes', $userId); 
        }  
    }

    /**
     * Remove the note from storage.
     * @param  int  $userId
     * @return \Illuminate\Http\Response(notes.blade.php)
     */
    public function destroyNote(int $userId)
    {
        if(Session::has('noteId'))
        {
            $noteId = session()->get('noteId');
            
            $note = UserResource::find($noteId);
            if($note == null || ($note->note_description == null && $note->note_url == null))
            {
                session()->put('error', 'Note does not exist.');
                return redirect()->route('user.notes', $userId);
            }
            if(($note->note_url != null) && file_exists($note->note_url))
                unlink($note->note_url);
            
            //Set appropriate columns to null
            $note->note_description = null;
            $note->note_url = null;

            //if other important data columns are empty, delete entire row
            if($note->event_name == null && $note->event_date == null && $note->event_time == null &&
                $note->event_description == null && $note->img_url == null && $note->img_description == null)
            {
                $updated->delete();
                session()->forget('noteId');
                session()->put('success', 'Note deleted successfully!.');
                return redirect()->route('user.notes', $userId);
            }        
            else    //if other columns contain data
            {
                $result = $note->save();
                if($result)
                {
                    session()->forget('noteId');
                    session()->put('success', 'Note deleted successfully!.');
                    return redirect()->route('user.notes', $userId);
                }
                else
                {
                    $note->save();
                    session()->forget('noteId');
                    session()->put('success', 'Note deleted successfully!.');
                    return redirect()->route('user.notes', $userId);
                }
            }
        }
        else    //If resourceId was not stored as a session
            return redirect()->route('user.notes', $userId);  
    }

/*----------------------------Notes Functions Ends Here------------------------------------------*/


/*----------------------------Calendar Functions Starts Here---------------------------------------- */
    /**
     * Display calendar.
     * @param  int $userId
     * @return \Illuminate\Http\Response(calendar.blade.php)
     */ 
    public function userCalendar(int $userId)
    {
        //Array to store database rows with unique event_date
        $refinedData = array();
        
        $data = UserResource::where('student_id', $userId)
                            ->select('event_date', 'resource_id')
                            ->where('event_date', '!=', 'NULL')
                            ->orderBy('event_date', 'asc')
                            ->get();
        if($data != null)
        {
            $check = null;
            foreach($data as $item)
            {
                if($check == $item->event_date)
                    continue;
                $refinedData[] = $item;
                $check = $item->event_date;
            }
            $data = $refinedData;
        }
        $userData = [
            'id' => $userId,
            'resource' => $data
            ];
        return view('user/calendar/calendar', $userData);
    }

    /**
     * Display calendar schedule page.
     * @param  int $userId
     * @return \Illuminate\Http\Response(schedule.blade.php)
     */ 
    public function setSchedule(int $userId)
    {
        return view('user/calendar/schedule', ['id'=>$userId]);
    }

    /**
     * Store a newly created schedule in database.
     * @param  \Illuminate\Http\Request  $request
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function storeSchedule(Request $request, int $userId)
    {
        //Validate the description
        $validate = Validator::make($request->all(), [
            'event_name'=>'required|string',
            'event_date'=>'required|date',
            'event_time'=>'required|date_format:H:i',
            'event_description'=>'required|string'
        ]);
        if($validate->fails())
        {
            session()->put('error', 'Invalid entry(ies).');
            return redirect()->route('user.calendar.schedule', $userId);
        }
        $validInput = $validate->validated();
        
        $data = UserResource::where('student_id', $userId)
                                ->select('resource_id', 'event_name', 'event_date', 'event_time', 'event_description')
                                ->where('event_name')
                                ->get();
        //If there is extra row-column(s) space in database
        if(count($data) > 0)
        {
            $extract = $data->first();
            $newScheduleId = $extract->resource_id;
            $newSchedule = UserResource::find($newScheduleId);
            $newSchedule->event_name = $validInput['event_name'];
            $newSchedule->event_date = $validInput['event_date'];
            $newSchedule->event_time = $validInput['event_time'];
            $newSchedule->event_description = $validInput['event_description'];
        }   
        else    //Create a new row in database
        {
            $newSchedule = new UserResource;
            $newSchedule->student_id = $userId;
            $newSchedule->event_name = $validInput['event_name'];
            $newSchedule->event_date = $validInput['event_date'];
            $newSchedule->event_time = $validInput['event_time'];
            $newSchedule->event_description = $validInput['event_description'];
        }
            
        if($newSchedule->save())        
        {
            session()->put('success', 'Event scheduled successfully!.');
            return redirect()->route('user.calendar', $userId);
        }
        else
        {
            session()->put('error', 'Event not scheduled, try again.');
            return redirect()->route('user.calendar', $userId);
        }
    }

    /**
     * Display calendar schedule(s) page.
     * @param  int $userId, $scheduleId
     * @param string $link
     * @return \Illuminate\Http\Response(details.blade.php)
     */ 
    public function viewSchedules(int $userId, int $scheduleId, string $link)
    {
        //variable to store details of a particular schedule if clicked
        $linkData = null;
        if($link == 'click')
        {
            $linkData = UserResource::where('resource_id', $scheduleId)
                            ->select('resource_id', 'event_name', 'event_date', 'event_time', 'event_description')
                            ->where('event_name', '!=', 'NULL')  
                            ->first();
            session()->put('scheduleEditId', $scheduleId);
        }
        //create a variable whose id is going to be used to form an orWhere clause
        //used to display the details of schedules with same event_date
        $compare = UserResource::find($scheduleId);

        $data = UserResource::where('resource_id', $scheduleId)
                            ->orWhere('event_date', $compare->event_date)
                            ->select('resource_id', 'event_name', 'event_date', 'event_time', 'event_description')
                            ->where('event_name', '!=', 'NULL')
                            ->orderBy('event_time', 'asc')  
                            ->get();
        $userData = [
            'resource' => $data,
            'id' => $userId,
            'linkData' => $linkData
        ];
        return view('user/calendar/details', $userData);
    }

    /**
     * Edit and store user schedule.
     * @param  Request $request, 
     * @param int $userId
     * @return \Illuminate\Http\Response(details.blade.php)
     */
    public function editSchedule(Request $request, int $userId)
    {
        if(Session::has('scheduleEditId'))
        {
            $link = 'none'; //variable to be passed as an argument to redirect route
            $resourceId = session()->get('scheduleEditId');

            //Validate edited schedule
            $validate = Validator::make($request->all(), [
                        'event_name'=>'required|string',
                        'event_date'=>'required|date',
                        'event_time'=>'required|date_format:H:i',
                        'event_description'=>'required|string'
                    ]);
        
            if($validate->fails())
            {
                session()->put('error', 'Invalid entry(ies).');
                return redirect()->route('user.calendar.details', [$userId, $resourceId, $link]);
            }
            $validInput = $validate->validated();

            $editedSchedule = UserResource::find($resourceId);
            if($editedSchedule == null || $editedSchedule->event_name == null)
            {
                session()->put('error', 'Schedule does not exist.');
                return redirect()->route('user.calendar', $userId);
            }
            $editedSchedule->event_name = $validInput['event_name'];
            $editedSchedule->event_date = $validInput['event_date'];
            $editedSchedule->event_time = $validInput['event_time'];
            $editedSchedule->event_description = $validInput['event_description'];
            if($editedSchedule->save())
            {
                session()->put('success', 'Schedule updated successfully!.');
                return redirect()->route('user.calendar.details', [$userId, $resourceId, $link]);
            }
            else
            {
                session()->put('error', 'Schedule not updated, try again.');
                return redirect()->route('user.calendar.details', [$userId, $resourceId, $link]);
            }
        }
        else    //If resourceId was not stored as a session
            return redirect()->route('user.calendar', $userId);
    }
    
    /**
     * Remove the schedule with a particular $userId from storage.
     * @param  int  $userId
     * @return \Illuminate\Http\Response(user.calendar || user.calendar.details)
     */
    public function destroySchedule(int $userId)
    {
        if(Session::has('scheduleEditId'))
        {
            $link = 'none'; //variable to be passed as an argument to redirect routes
            //variables to store the result of delete and save respectively
            $result1 = false;
            $result2 = false;

            $resourceId = session()->get('scheduleEditId');
            $delSchedule = UserResource::find($resourceId);
            if($delSchedule == null || $delSchedule->event_name == null)
            {
                session()->put('error', 'Schedule does not exist.');
                return redirect()->route('user.calendar', $userId);
            }

            //eventDate which will be used to check if there is any 
            //other schedules in the database with the same event_date
            $delScheduleEventDate = $delSchedule->event_date;

            //Set to null and save
            $delSchedule->event_name = null;
            $delSchedule->event_date = null;
            $delSchedule->event_time = null;
            $delSchedule->event_description = null;

            if($delSchedule->img_url == null && $delSchedule->img_description == null && 
                $delSchedule->note_url == null && $delSchedule->note_description == null )
                    $result1 = $delSchedule->delete();
            else
                $result2 = $delSchedule->save();

            if($result1 == true || $result2 == true)
            {
                $data = UserResource::where('event_date', $delScheduleEventDate)
                                    ->select('resource_id')
                                    ->first();
                if($data != null)
                {
                    session()->forget('scheduleEditId');
                    session()->put('success', 'Schedule deleted successfully!.');
                    return redirect()->route('user.calendar.details', [$userId, $data->resource_id, $link]);
                }
                else
                {
                    session()->forget('scheduleEditId');
                    session()->put('success', 'Schedule deleted successfully!.');
                    return redirect()->route('user.calendar', $userId);   
                }
                    
            }
            else    //if deletion (entire or selected columns) was not saved
            {
                session()->put('error', 'Schedule not deleted, try again.');
                return redirect()->route('user.calendar.details', [$userId, $resourceId, $link]);
            }
        }
        else    //If resourceId was not stored as a session
            return redirect()->route('user.calendar', $userId);
    }

    /**
     * Remove the schedules with the same event_date entirely from storage.
     * @param  int  $userId
     * @return \Illuminate\Http\Response(user.calendar)
     */
    public function destroySchedules(int $userId)
    {
        if(Session::has('scheduleEditId'))
        {
            $resourceId = session()->get('scheduleEditId');
            $delSchedule = UserResource::find($resourceId);
            if($delSchedule == null || $delSchedule->event_date == null)
            {
                session()->put('error', 'Schedule(s) does not exist.');
                return redirect()->route('user.calendar', $userId);
            }
            //variable whose event_date will be used to   
            //retrieve database rows with the same event_date
            $tempDate = $delSchedule->event_date;

            $linkData = UserResource::where('event_date', $tempDate)  
                                    ->get();
            foreach($linkData as $item)
            {
                $itemResourceId = $item->resource_id;
                //Set to null
                $item->event_name = null;
                $item->event_date = null;
                $item->event_time = null;
                $item->event_description = null;
                if($item->img_url == null && $item->img_description == null && 
                    $item->note_url == null && $item->note_description == null)
                    $item->delete();
                else
                    $item->save();  
            }
            session()->put('success', 'Schedule(s) deleted successfully!.');
            return redirect()->route('user.calendar', $userId);
        }
        else    //If resourceId was not stored as a session
            return redirect()->route('user.calendar', $userId);
    }

/*----------------------------Calendar Functions Ends Here---------------------------------------- */


/*----------------------------Timetable Functions Starts Here----------------------------------------*/

    /**
     * Display a the user timetable(s).
     * @param  int $userId
     * @return \Illuminate\Http\Response(timetable.blade.php)
     */
    public function userTimetable(int $userId)
    {  
        $data = UserResource::where('student_id', $userId)
                                ->select('img_description', 'resource_id')
                                ->where('img_description', '!=', 'NULL')
                                ->orderBy('img_description', 'asc')
                                ->get();
        $userData = [
            'id' => $userId,
            'resource' => $data
        ];
        return view('user/timetable/timetable', $userData);
    }

    /**
     * Store a newly uploaded timetable(img) in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param int $userId
     * @return \Illuminate\Http\Response(timetable.blade.php)
     */
    public function storeTimetable(Request $request, int $userId)
    {
        //Validate the description
        $validate = Validator::make($request->all(), [
            'img_description'=>'required|string|unique:user_resources'
        ]);
     
        if($validate->fails())
        {
            session()->put('error', $validate->errors()->first());
            return redirect()->route('user.timetable', $userId);
        }
        $validInput = $validate->validated();

        //Start Image Processing
        $mainFile = $request->file('image');
        $imgName = $mainFile->getClientOriginalName();
        $imgExtension = $mainFile->getClientOriginalExtension();

        if($imgExtension == "png" || $imgExtension == "jpeg" || $imgExtension == "jpg")
        {
            $getSize = $mainFile->getSize();
            if($getSize < 2097152)
            {
                $ran = uniqid("img_");   //timestamp to be used in renaming file
                $newImgName =  $ran.'.'.$imgExtension;  //Create new file name for uploaded file
                $imgUrl = "storage/timetables/{$newImgName}"; //path to image
                $mainFile->storeAs('/timetables', $newImgName, 'public');

                $data = UserResource::where('user_resources.student_id', $userId)
                                        ->select('resource_id', 'img_url', 'img_description')
                                        ->where('img_url')
                                        ->orWhere('img_description')
                                        ->get();
                //If there is any existing row- column space for storage
                if(count($data) > 0)
                {
                    $extract = $data->first();
                    $newTimetableId = $extract->resource_id;
                    $newTimetable = UserResource::find($newTimetableId);
                    $newTimetable->img_url = $imgUrl;
                    $newTimetable->img_description = $validInput['img_description'];
                }   
                else    //Create a new row
                {
                    $newTimetable = new UserResource;
                    $newTimetable->student_id = $userId;
                    $newTimetable->img_url = $imgUrl;
                    $newTimetable->img_description = $validInput['img_description'];
                }

                if($newTimetable->save())        
                {
                    session()->put('success', 'Timetable uploaded successfully!.');
                    return redirect()->route('user.timetable', $userId);
                }
                else
                {
                    session()->put('error', 'Timetable not uploaded.');
                    return redirect()->route('user.timetable', $userId);
                }
            }
            else//if image exceeds size
            {
                session()->put('error', 'Image size limit exceeded.');
                return redirect()->route('user.timetable', $userId);
            }
        }
        else    //if file is not of the required type
        {
            session()->put('error', 'Image is not of the required type.');
            return redirect()->route('user.timetable', $userId);
        }
    }

    /**
     * Display a view of the user timetable.
     * @param  int $userId, $resourceId
     * @return \Illuminate\Http\Response(details.blade.php)
     */
    public function viewTimetable(int $userId, int $resourceId)
    {
        $data = UserResource::where('resource_id', $resourceId)
                            ->select('resource_id', 'img_description', 'img_url')
                            ->where('img_url', '!=', 'NULL')
                            ->first();
        if($data != null)
        {
            session()->put('timetableEditId', $resourceId);
            $userData = [
                'id' => $userId,
                'resource' => $data
                ];
            return view('user/timetable/details', $userData);
        }
        else
        {
            return redirect()->route('user.timetable', $userId);
        }
    }

    /**
     * Display a view of the user timetable.
     * @param Request $request
     * @param  int $userId
     * @return \Illuminate\Http\Response(details.blade.php)
     */
    public function editTimetable(Request $request, int $userId)
    {
        if(Session::has('timetableEditId'))
        {
            $resourceId = session()->get('timetableEditId');

            //Validate the description
            $validate = Validator::make($request->all(), [
                    'img_description'=>'required|string|unique:user_resources'
                    ]);
            
            if($validate->fails())
            {
                session()->put('error', $validate->errors()->first());
                return redirect()->route('user.timetable.details', [$userId, $resourceId]);
            }
            $validInput = $validate->validated();

            $description = UserResource::find($resourceId);
            if($description == null || $description->img_description == null)
            {
                session()->put('error', 'Timetable does not exist.');
                return redirect()->route('user.timetable', $userId);
            }
            $description->img_description = $validInput['img_description'];
            if($description->save())
            {
                session()->put('success', 'Timetable updated successfully!.');
                return redirect()->route('user.timetable.details', [$userId, $resourceId]);
            }
                
            else
            {
                session()->put('error', 'Timetable not updated, try again.');
                return redirect()->route('user.timetable.details', [$userId, $resourceId]);
            }
        }
        else    //If resourceId was not stored as a session
            return redirect()->route('user.timetable', $userId);
        
    }

    /**
     * Remove the timetable from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyTimetable(int $userId)
    {
        if(Session::has('timetableEditId'))
        {
            $resourceId = session()->get('timetableEditId');
            $description = UserResource::find($resourceId);
            if($description == null || $description->img_url == null)
            {
                session()->put('error', 'Timetable does not exist.');
                return redirect()->route('user.timetable', $userId);
            }
            if(($description->img_url != null) && file_exists($description->img_url))
                unlink($description->img_url);
            //Set appropriate columns to nul
            $description->img_description = null;
            $description->img_url = null;

            //if other important data columns are empty, delete entire row
            if($description->event_name == null && $description->event_date == null && $description->event_time == null 
                && $description->event_description == null && 
                $description->note_url == null && $description->note_description == null)
                {
                    $updated->delete();
                    session()->forget('timetableEditId');
                    session()->put('success', 'Timetable deleted successfully!.');
                    return redirect()->route('user.timetable', $userId);
                }
            else    //if other columns contains data
            {
                $result = $description->save();
                if($result)
                {
                    session()->forget('timetableEditId');
                    session()->put('success', 'Timetable deleted successfully!.');
                    return redirect()->route('user.timetable', $userId);
                }
                else
                {
                    $description->save();
                    session()->forget('timetableEditId');
                    session()->put('success', 'Timetable deleted successfully!.');
                    return redirect()->route('user.timetable', $userId);
                }
            }
        }
        else    //If resourceId was not stored as a session
            return redirect()->route('user.timetable', $userId);
    }

/*----------------------------Timetable Functions Ends Here------------------------------------------*/


    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return redirect()->route('pages.home');
    }
}