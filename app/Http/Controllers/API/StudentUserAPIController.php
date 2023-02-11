<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Student;
use App\Models\UserResource;

class StudentUserAPIController extends Controller
{
    /**
     * Retrieve user biodata.
     * 
     * @param  int $userId
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function dashboard(int $userId):JsonResponse
    {
        try 
        {
            $data = Student::find($userId);
            if($data != null)
            {
                $userData = [
                            'id' => $userId,
                            'resource' => $data
                        ];
                return response()->json([
                            'status' => 'success',
                            'message' => 'User details retrieved!.',
                            'data' => $userData
                            ], 200);
                //return view('user/dashboard', $userData);
            }
            else
            {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'User does NOT exist!'
                ], 400);
            }
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User details NOT retrieved.',
                'data' => null
            ], 400);
        }
        
    }

/*----------------------------Calendar API Functions Starts Here---------------------------------------- */
    /**
     * list calendar resources with unique event_date.
     * 
     * @param  int $userId
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function calendar(int $userId):JsonResponse
    {
        try 
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

            return response()->json([
                    'status' => 'success',
                    'message' => 'Calendar Resource(s) retrieved.',
                    'data' => $userData 
                ], 200);    
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Calendar Resource(s) NOT retrieved.'.$e->getMessage(),
                'data' => null 
            ], 400);
        }
    }

    /**
     * Store a newly created schedule in database.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSchedule(Request $request, int $userId):JsonResponse
    {
        try
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
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Input validation failed: '.$validate->errors()->first(),
                    'data' => null
                ], 412);
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
                return response()->json([
                    'status' => 'success',
                    'message' => 'Event scheduled successfully!.',
                    'data' => $newSchedule
                ], 201);
            }
            else
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event NOT scheduled!.',
                    'data' => null
                ], 400);
            }    
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Event NOT scheduled: '.$e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    /**
     * list calendar schedule(s) with particular id or same event_date.
     * 
     * @param  int $userId, $scheduleId
     * @param string $link
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function viewSchedules(int $userId, int $scheduleId, string $link):JsonResponse
    {
        try 
        {
            $check = UserResource::find($scheduleId);
            if($check != null)
            {
                //variable to store details of a particular schedule if clicked
                $linkData = null;
                if($link == 'click')
                {
                    $linkData = UserResource::where('resource_id', $scheduleId)
                                            ->select('resource_id', 'event_name', 'event_date', 'event_time', 'event_description')
                                            ->where('event_name', '!=', 'NULL')  
                                            ->first();
                    //session()->put('scheduleEditId', $scheduleId);
                }
                

                $data = UserResource::where('resource_id', $scheduleId)
                                    ->orWhere('event_date', $check->event_date)
                                    ->select('resource_id', 'event_name', 'event_date', 'event_time', 'event_description')
                                    ->where('event_name', '!=', 'NULL')
                                    ->orderBy('event_time', 'asc')  
                                    ->get();
                $userData = [
                    'resource' => $data,
                    'id' => $userId,
                    'linkData' => $linkData
                ];
                return response()->json([
                    'status' => 'success',
                    'message' => 'Schedule(s) retrieved.',
                    'data' => $userData 
                ], 200); 
            }
            else
            {
                return response()->json([
                            'status' => 'warning',
                            'message' => 'Schedule(s) does NOT exist!',
                            'data' => $userId
                        ], 400);
            }    
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule(s) NOT retrieved:'.$e->getMessage(),
                'data' => null 
            ], 400);
        }
    }

    /**
     * Edit and store user schedule.
     * 
     * @param  Request $request, 
     * @param int $userId, $resourceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editSchedule(Request $request, int $userId, int $resourceId):JsonResponse
    {
        try 
        {
            $editedSchedule = UserResource::find($resourceId);
            if($editedSchedule != null && $editedSchedule->event_name != null)
            {
                //$link = 'none'; //variable to be passed as an argument to redirect route
            //$resourceId = session()->get('scheduleEditId');

            //Validate edited schedule
            $validate = Validator::make($request->all(), [
                'event_name'=>'required|string',
                'event_date'=>'required|date',
                'event_time'=>'required|date_format:H:i',
                'event_description'=>'required|string'
            ]);

            if($validate->fails())
            {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Input validation failed: '.$validate->errors()->first(),
                    'data' => null
                ], 412);
            }
            $validInput = $validate->validated();

            $editedSchedule->event_name = $validInput['event_name'];
            $editedSchedule->event_date = $validInput['event_date'];
            $editedSchedule->event_time = $validInput['event_time'];
            $editedSchedule->event_description = $validInput['event_description'];
            if($editedSchedule->save())
            {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Schedule Updated successfully!.',
                    'data' => $editedSchedule
                ], 201);
                //session()->put('success', 'Schedule updated successfully!.');
                //return redirect()->route('user.calendar.details', [$userId, $resourceId, $link]);
            }
            else
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Schedule NOT updated.',
                    'data' => null
                ], 400);
                //session()->put('error', 'Schedule not updated, try again.');
                //return redirect()->route('user.calendar.details', [$userId, $resourceId, $link]);
            }
            }
            else
            {
                return response()->json([
                            'status' => 'warning',
                            'message' => 'Schedule does NOT exist.'
                        ], 400);
            }
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule NOT updated: '.$e->getMessage(),
                'data' => null
            ], 400);
        }
    }
    
    /**
     * Remove the schedule with a particular $userId from storage.
     * 
     * @param  int  $userId, $resourceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroySchedule(int  $userId, int $resourceId):JsonResponse
    {
        try 
        {
            $delSchedule = UserResource::find($resourceId);
            if($delSchedule != null)
            {
                //variables to store the result of delete and save respectively
                $result1 = false;
                $result2 = false;

                //$resourceId = session()->get('scheduleEditId');
                

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
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Event deleted successfully!.',
                            'data' => $data
                        ], 201);
                        //session()->forget('scheduleEditId');
                        //session()->put('success', 'Schedule deleted successfully!.');
                        //return redirect()->route('user.calendar.details', [$userId, $data->resource_id, $link]);
                    }
                    else
                    {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Schedule deleted successfully!.',
                            'data' => $userId
                        ], 201);
                        //session()->forget('scheduleEditId');
                        //session()->put('success', 'Schedule deleted successfully!.');
                        //return redirect()->route('user.calendar', $userId);   
                    }
                        
                }
                else    //if deletion (entire or selected columns) was not saved
                {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Schedule NOT deleted.',
                        'data' => $userId
                    ], 201);
                    //session()->put('error', 'Schedule not deleted, try again.');
                    //return redirect()->route('user.calendar.details', [$userId, $resourceId, $link]);
                }
            }
            else
            {
                return response()->json([
                            'status' => 'warning',
                            'message' => 'Schedule does NOT exist.',
                            'data' => $userId
                        ], 400);
            }
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Schedule NOT deleted: '.$e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    /**
     * Remove the schedules with the same event_date entirely from storage.
     * 
     * @param  int  $userId, $resourceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroySchedules(int $userId, int $resourceId):JsonResponse
    {
        try 
        {
            $delSchedule = UserResource::find($resourceId);
            if($delSchedule != null)
            {
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
                return response()->json([
                    'status' => 'success',
                    'message' => 'Schedule(s) deleted successfully!'
                ], 200);
                //session()->put('success', 'Schedule(s) deleted successfully!.');
                //return redirect()->route('user.calendar', $userId);
            }
            else
            {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Schedule(s) does NOT exist!'
                ], 400);
            }  
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occured:'.$e->getMessage()
            ], 400);
        }
    }

/*----------------------------Calendar Functions Ends Here---------------------------------------- */

/*----------------------------Notes API Functions Starts Here----------------------------------------*/

    /**
     * list user's note(s).
     * 
     * @param  int $userId
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function userNotes(int $userId):JsonResponse
    {
        try 
        {
            //Retrieve user notes if any
            $data = UserResource::where('student_id', $userId)
            ->select('note_description', 'resource_id')
            ->where('note_description', '!=', 'NULL')
            ->get();
            $userData = [
                    'id' => $userId,
                    'resource' => $data
                    ];
            return response()->json([
                'status' => 'success',
                'message' => 'User Note(s) retrieved.',
                'data' => $userData
                ], 200);
            //return view('user/notes/notes', $userData);
        }  
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User Notes NOT retrieved: '.$e->getMessage(),
                'data' => $userId
            ], 400);
        }
    }

    /**
     * Store a newly uploaded note in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNotes(Request $request, int $userId):JsonResponse
    {
        try 
        {
            //Validate the description
            $validate = Validator::make($request->all(), [
                'note_description'=>'required|string'
                ]);
            if($validate->fails())
            {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Input validation failed: '.$validate->errors()->first(),
                    'data' => $userId
                 ], 412);
                //session()->put('error', 'Invalid description.');
                //return redirect()->route('user.notes', $userId);
            }
            $validInput = $validate->validated();

            /*
            //Start Image Processing
            $mainFile = $request->file('document');
            $docName = $mainFile->getClientOriginalName();
            $docExtension = $mainFile->getClientOriginalExtension();
            $getSize = $mainFile->getSize();
            if($getSize > 5242880)
            {
                session()->put('error', 'File size limit exceeded.');
                return redirect()->route('user.notes', $userId);
            }
            $ran = uniqid("doc_");   //timestamp to be used in renaming file
            $newDocName =  $ran.'.'.$docExtension;  //Create new file name for uploaded file
            $docUrl = "storage/notes/{$newDocName}"; //path to document
            $mainFile->storeAs('/notes', $newDocName, 'public');
            */

            //save resource in storage
            $data = UserResource::where('user_resources.student_id', $userId)
                                    ->select('resource_id', 'note_url', 'note_description')
                                    ->where('note_description')
                                    //->orwhere('note_url')
                                    ->get();
            //Check if there is any existing row- column space for storage
            if(count($data) > 0)
            {
                $extract = $data->first();
                $newNoteId = $extract->resource_id;
                //Insert resource into an already existing row in database
                $newNote = UserResource::find($newNoteId);
                //$newNote->note_url = $docUrl;
                $newNote->note_description = $validInput['note_description'];
            }   
            else
            //Create a new one
            {
                $newNote = new UserResource;
                $newNote->student_id = $userId;
                //$newNote->note_url = $docUrl;
                $newNote->note_description = $validInput['note_description'];
            }

            //Save resource
            if($newNote->save())        
            {
                return response()->json([
                                'status' => 'success',
                                'message' => 'File uploaded successfully!',
                                'data' => $userId
                            ], 201);
                //session()->put('success', 'File uploaded successfully!.');
                //return redirect()->route('user.notes', $userId);
            }
            else
            {
                return response()->json([
                                'status' => 'error',
                                'message' => 'File NOT uploaded.',
                                'data' => $userId
                            ], 400);
                //session()->put('error', 'File not uploaded, try again.');
                //return redirect()->route('user.notes', $userId);
            }
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'File NOT uploaded: '.$e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    /**
     * Retrieve details of single user note.
     * 
     * @param  int $userId, $noteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewNote(int $userId, int $noteId):JsonResponse
    {
        try 
        {
            //$noteId for use in update and deletion
            //session()->put('noteId', $noteId);
            
            $data = UserResource::where('resource_id', $noteId)
                                    ->select('note_description', 'note_url', 'resource_id')
                                    //->where('note_url', !'NULL')
                                    ->where('note_description', '!=', 'NULL')
                                    ->first();
            $userData = [
                        'resource' => $data,
                        'id' => $userId
                    ];
            return response()->json([
                'status' => 'success',
                'message' => 'User Note details retrieved!.',
                'data' => $userData
                ], 200);
            //return view('user/notes/details', $userData);
        } 
        catch (Exception $e) 
        {
            return response()->json([
                            'status' => 'error',
                            'message' => 'User Note details NOT retrieved.',
                            'data' => null
                        ], 400);
        }
    }

    /**
     * Update user_note file description
     * 
     * @param  Request $request
     * @param  int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNote(Request $request, int $userId, int $noteId):JsonResponse
    {
        try 
        {
            //if(Session::has('noteId'))
            //{
                //$noteId = session()->get('noteId');

            //Validate the description
            $validate = Validator::make($request->all(), [
                'note_description'=>'required|string'
                ]);

            if($validate->fails())
            {
                return response()->json([
                            'status' => 'warning',
                            'message' => 'Input validation failed: '.$validate->errors()->first(),
                            'data' => [$userId, $noteId]
                        ], 412);
                //session()->put('error', 'Invalid description.');
                //return redirect()->route('user.notes.details', [$userId, $noteId]);
            }
            $validInput = $validate->validated();

            $description = UserResource::find($noteId);
            if($description->note_description != null)
            {
                $description->note_description = $validInput['note_description'];

                if($description->save())
                {
                    return response()->json([
                                'status' => 'success',
                                'message' => 'Description updated successfully!',
                                'data' => [$userId, $noteId]
                            ], 200);
                    //session()->put('success', 'Description updated successfully!.');
                    //return redirect()->route('user.notes.details', [$userId, $noteId]);
                }
                else
                {
                    return response()->json([
                                'status' => 'error',
                                'message' => 'Description NOT updated!',
                                'data' => [$userId, $noteId]
                            ], 400);
                    //session()->put('error', 'Description not updated, try again.');
                    //return redirect()->route('user.notes.details', [$userId, $noteId]);
                }
            }
            //}
            //else
            //If resourceId was not stored as a session
            //{
                //return redirect()->route('user.notes', $userId); 
            //}  
        } 
        catch (Exception $e) 
        {
            return response()->json([
                            'status' => 'error',
                            'message' => '[$userId, $noteId] NOT updated: '.$e->getMessage(),
                            'data' => null
                        ], 400);
        }
    }

    /**
     * Remove user note from storage.
     * 
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyNote(int $userId, int $noteId):JsonResponse
    {
        try 
        {
            //if(Session::has('noteId'))
            //{
            //$noteId = session()->get('noteId');
            
            $note = UserResource::find($noteId);
            if($noteId != null)
            {
                //if(($note->note_url != null) && file_exists($note->note_url))
                //unlink($note->note_url);
            
                //Set appropriate columns to null
                $note->note_description = null;
                $note->note_url = null;

                //if other important data columns are empty, delete entire row
                if($note->event_name == null && $note->event_date == null && $note->event_time == null &&
                    $note->event_description == null && $note->img_url == null && $note->img_description == null)
                {
                    $updated->delete();
                    return response()->json([
                                'status' => 'success',
                                'message' => 'User Note deleted successfully!',
                                'data' => $userId
                            ], 200);
                    //session()->forget('noteId');
                    //session()->put('success', 'File deleted successfully!.');
                    //return redirect()->route('user.notes', $userId);
                }        
                else    //if other columns contain data
                {
                    $result = $note->save();
                    if($result)
                    {
                        return response()->json([
                                    'status' => 'success',
                                    'message' => 'User Note deleted successfully!',
                                    'data' => $userId
                                ], 200);
                        //session()->forget('noteId');
                        //session()->put('success', 'File deleted successfully!.');
                        //return redirect()->route('user.notes', $userId);
                    }
                    else
                    {
                        $note->save();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'User Note deleted successfully!',
                            'data' => $userId
                        ], 200);
                        //session()->forget('noteId');
                        //session()->put('success', 'File deleted successfully!.');
                        //return redirect()->route('user.notes', $userId);
                    }
                }
                //}
                //else    //If resourceId was not stored as a session
                    //return redirect()->route('user.notes', $userId);  
            }
            else
            {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Note does NOT exist!',
                    'data' => $userId
                ], 400);
            }
        } 
        catch (Exception $e)
        {
            return response()->json([
                        'status' => 'error',
                        'message' => 'Error occured:'.$e->getMessage()
                    ], 400);
        }
    }

/*----------------------------Notes API Functions Ends Here------------------------------------------*/

/*----------------------------Timetable Functions Starts Here----------------------------------------*/

    /**
     * Display a list of user timetable(s).
     * 
     * @param  int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function userTimetable(int $userId):JsonResponse
    {  
        try 
        {
            $data = UserResource::where('student_id', $userId)
                                ->select('img_description', 'resource_id')
                                ->where('img_description', !'NULL')
                                ->get();
            $userData = [
                        'id' => $userId,
                        'resource' => $data
                    ];
            return response()->json([
                        'status' => 'success',
                        'message' => 'User Timetable retrieved.',
                        'data' => $userData
                    ], 200);
            //return view('user/timetable/timetable', $userData);
        } 
        catch (Exception $e)
        {
            return response()->json([
                            'status' => 'error',
                            'message' => 'User Timetable NOT retrieved: '.$e->getMessage(),
                            'data' => null
                        ], 400);
        }
    }

    /**
     * Store a newly uploaded timetable(img) in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTimetable(Request $request, int $userId):JsonResponse
    {
        try 
        {
            //Validate the description
            $validate = Validator::make($request->all(), [
                'img_description'=>'required|string'
            ]);
        
            if($validate->fails())
            {
                return response()->json([
                            'status' => 'warning',
                            'message' => 'Input validation failed: '.$validate->errors()->first(),
                            'data' => $userId
                        ], 412);
                //session()->put('error', 'Invalid description.');
                //return redirect()->route('user.timetable', $userId);
            }
            $validInput = $validate->validated();

            /*
            //Start Image Processing
            $mainFile = $request->file('image');
            $imgName = $mainFile->getClientOriginalName();
            $imgExtension = $mainFile->getClientOriginalExtension();
            if($imgExtension != "png" || $imgExtension != "jpeg" || $imgExtension != "jpg")
            {
                session()->put('error', 'Image is not of the required type.');
                return redirect()->route('user.timetable', $userId);
            }
            $getSize = $mainFile->getSize();
            if($getSize > 2097152)
            {
                session()->put('error', 'Image size limit exceeded.');
                return redirect()->route('user.timetable', $userId);
            }
            $ran = uniqid("img_");   //timestamp to be used in renaming file
            $newImgName =  $ran.'.'.$imgExtension;  //Create new file name for uploaded file
            $imgUrl = "storage/timetables/{$newImgName}"; //path to image
            $mainFile->storeAs('/timetables', $newImgName, 'public');
            */

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
                //$newTimetable->img_url = $imgUrl;
                $newTimetable->img_description = $validInput['img_description'];
            }   
            else    //Create a new row
            {
                $newTimetable = new UserResource;
                $newTimetable->student_id = $userId;
                //$newTimetable->img_url = $imgUrl;
                $newTimetable->img_description = $validInput['img_description'];
            }

            if($newTimetable->save())        
            {
                return response()->json([
                            'status' => 'success',
                            'message' => 'Timetable uploaded successfully!.',
                            'data' => $userId
                        ], 201);
                //session()->put('success', 'Timetable uploaded successfully!.');
                //return redirect()->route('user.timetable', $userId);
            }
            else
            {
                return response()->json([
                            'status' => 'error',
                            'message' => 'Timetable not uploaded.',
                            'data' => $userId
                        ], 400);
                //session()->put('error', 'Timetable not uploaded.');
                //return redirect()->route('user.timetable', $userId);
            } 
        } 
        catch (Exception $e) 
        {
            return response()->json([
                        'status' => 'error',
                        'message' => 'Timetable NOT uploaded: '.$e->getMessage(),
                        'data' => null
                    ], 400);
        }
    }

    /**
     *Retrieve details of the user timetable.
     * 
     * @param  int $userId, $resourceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewTimetable(int $userId, int $resourceId):JsonResponse
    {
        try 
        {
            //session()->put('timetableEditId', $resourceId);
            $data = UserResource::where('resource_id', $resourceId)
                                ->select('resource_id', 'img_description', 'img_url')
                                ->where('img_url', '!=', 'NULL')
                                ->first();
            $userData = [
                    'id' => $userId,
                    'resource' => $data
                    ];
            return response()->json([
                        'status' => 'success',
                        'message' => 'Timetable details retrieved!.',
                        'data' => $userData
                        ], 200);
            //return view('user/timetable/details', $userData);
        } 
        catch (Exception $e) {
            return response()->json([
                        'status' => 'error',
                        'message' => 'Timetable details NOT retrieved:'.$e->getMessage(),
                        'data' => null
                    ], 400);
        }
    }

    /**
     * Display a view of the user timetable.
     * 
     * @param Request $request
     * @param  int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTimetable(Request $request, int $userId, int $resourceId):JsonResponse
    {
        try 
        {
            //if(Session::has('timetableEditId'))
            //{
            //$resourceId = session()->get('timetableEditId');

            //Validate the description
            $validate = Validator::make($request->all(), [
                    'img_description'=>'required|string'
                    ]);
            
            if($validate->fails())
            {
                return response()->json([
                            'status' => 'warning',
                            'message' => 'Input validation failed: '.$validate->errors()->first(),
                            'data' => null
                        ], 412);
                //session()->put('error', 'Invalid description.');
                //return redirect()->route('user.timetable.details', [$userId, $resourceId]);
            }
            $validInput = $validate->validated();

            $description = UserResource::find($resourceId);
            if($description->img_description != null)
            {
                $description->img_description = $validInput['img_description'];
                if($description->save())
                {
                    return response()->json([
                                'status' => 'success',
                                'message' => 'Timetable updated successfully!.',
                                'data' => [$userId, $resourceId]
                            ], 200);
                    //session()->put('success', 'Timetable updated successfully!.');
                    //return redirect()->route('user.timetable.details', [$userId, $resourceId]);
                }
                else
                {
                    return response()->json([
                                'status' => 'error',
                                'message' => 'Timetable NOT updated!',
                                'data' => [$userId, $resourceId]
                            ], 400);
                    //session()->put('error', 'Timetable not updated, try again.');
                    //return redirect()->route('user.timetable.details', [$userId, $resourceId]);
                }
                //}
                //else    //If resourceId was not stored as a session
                //return redirect()->route('user.timetable', $userId);
            }
            else
            {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Timetable does NOT exist!',
                    'data' => [$userId, $resourceId]
                ], 400);
            }
        }  
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Timetable NOT updated: '.$e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    /**
     * Remove the timetable from storage.
     *
     * @param  int  $userId, $resourceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTimetable(int $userId, int $resourceId):JsonResponse
    {
        try 
        {
            //if(Session::has('timetableEditId'))
            //{
            //$resourceId = session()->get('timetableEditId');
            $description = UserResource::find($resourceId);
            if($description->exists())
            {
                //if(($description->img_url != null) && file_exists($description->img_url))
                    //unlink($description->img_url);
                //Set appropriate columns to nul
                $description->img_description = null;
                $description->img_url = null;

                //if other important data columns are empty, delete entire row
                if($description->event_name == null && $description->event_date == null && $description->event_time == null 
                    && $description->event_description == null && 
                    $description->note_url == null && $description->note_description == null)
                    {
                        $updated->delete();
                        return response()->json([
                                    'status' => 'success',
                                    'message' => 'Timetable deleted successfully!.',
                                    'data' => $userId
                                ], 200);
                        //session()->forget('timetableEditId');
                        //session()->put('success', 'Timetable deleted successfully!.');
                        //return redirect()->route('user.timetable', $userId);
                    }
                else    //if other columns contains data
                {
                    $result = $description->save();
                    if($result)
                    {
                        return response()->json([
                                    'status' => 'success',
                                    'message' => 'Timetable deleted successfully!.',
                                    'data' => $userId
                                ], 200);
                        //session()->forget('timetableEditId');
                        //session()->put('success', 'Timetable deleted successfully!.');
                        //return redirect()->route('user.timetable', $userId);
                    }
                    else
                    {
                        $description->save();
                        return response()->json([
                                    'status' => 'success',
                                    'message' => 'Timetable deleted successfully!.',
                                    'data' => $userId
                                ], 200);
                        //session()->forget('timetableEditId');
                        //session()->put('success', 'Timetable deleted successfully!.');
                        //return redirect()->route('user.timetable', $userId);
                    }
                }
                //}
                //else    //If resourceId was not stored as a session
                    //return redirect()->route('user.timetable', $userId);
            }
            else    //if Timetable with $resourceId does not exist in database
            {
                return response()->json([
                            'status' => 'warning',
                            'message' => 'Timetable does NOT exist!.',
                            'data' => $userId
                        ], 400);
            }
        } 
        catch (Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Timetable NOT deleted:'.$e->getMessage(),
                'data' => null
            ], 400);
        }
    }

/*----------------------------Timetable Functions Ends Here------------------------------------------*/

}

?>
