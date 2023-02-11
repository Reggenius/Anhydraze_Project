<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentUserAPIController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['prefix' => 'v1'], function(){

    Route::controller(StudentUserAPIController::class)
    ->prefix('user/{id}')
    ->group(function(){
        Route::get('', 'dashboard');  //user_dashboard page

        Route::get('note', 'userNotes');  //retrieve user notes
        Route::post('note', 'storeNotes');  //store user_timetable
        Route::get('note/{noteId}', 'viewNote');  //retrieve user_timetable details
        Route::patch('note/{noteId}', 'updateNote');  //update user_note details
        Route::delete('note/{noteId}', 'destroyNote');  //delete user_note


        Route::get('calendar', 'calendar');  //retrieve user_schedules
        Route::post('calendar', 'storeSchedule');  //store user_schedule
        Route::get('calendar/{scheduleId}/{link}', 'viewSchedules');  //retrieve user_schedule details
        Route::patch('calendar/{resourceId}', 'editSchedule');  //update user_schedule details
        Route::delete('calendar/delete/{resourceId}', 'destroySchedule');  //delete user_schedule
        Route::delete('calendar/destroy/{resourceId}', 'destroySchedules');  //destroy user_schedule(s)
        

        Route::get('timetable', 'userTimetable');  //retrieve user_timetable
        Route::post('timetable', 'storeTimetable');  //store user_timetable
        Route::get('timetable/{resourceId}', 'viewTimetable');  //retrieve user_timetable details
        Route::patch('timetable/{resourceId}', 'editTimetable');  //update user_timetable details
        Route::delete('timetable/{resourceId}', 'destroyTimetable');  //delete user_timetable
        
        //Route::get('logout', 'logout');  //user_logout action
    });

    //API routes
    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function () {
        Route::controller(AuthController::class)->group(function(){
            Route::post('/login', 'login');
            Route::post('logout', 'logout');
            Route::post('/refresh', 'refresh');
            Route::post('/user', 'getUser');
        });
    });

});
