<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentUserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::fallback([StudentController::class, 'error404'])->name('pages.errro404');

Route::controller(StudentController::class)
->prefix('/scholarpal')
->group(function(){
    Route::get('', 'homepage')->name('pages.home'); //scholarpal index
    Route::get('/signup', 'signup')->name('pages.signup');  //signup
    Route::post('/add_user', 'addUser')->name('user.add');  //store user
    Route::get('/login', 'login')->name('user.login');  //login
    Route::post('/login/validate', 'validateLogin')->name('user.validLogin');  //login logic
    Route::get('/unenabled', 'createSession')->name('pages.session'); //unincluded features
    //Route::post('/password/email', 'email')->name('pages.password.email'); //forget password form
});


Route::middleware('auth:student')
->group(function(){
    Route::controller(StudentUserController::class)
    ->prefix('/scholarpal/user{id}')
    ->name('user.')
    ->group(function(){
        Route::get('', 'dashboard')->name('index');  //user_dashboard page
        Route::get('calculator', 'calculator')->name('calc');  //user_notes page

        /**-----------------------------------------------User Note Routes------------------------------------------------------------ */
        Route::get('notes', 'userNotes')->name('notes');  //user_notes page
        Route::post('notes/upload', 'storeNotes')->name('notes.upload');  //upload user_timetable
        Route::get('notes/view{noteId}', 'viewNote')->name('notes.details');  //view user_timetable details
        Route::post('notes/edit', 'editNote')->name('notes.edit');  //edit user_note details
        Route::get('notes/delete', 'destroyNote')->name('notes.delete');  //delete user_note

        /**-----------------------------------------------User Calendar Routes------------------------------------------------------------ */
        Route::get('calendar', 'userCalendar')->name('calendar');  //user_calendar page
        Route::get('calendar/schedule', 'setSchedule')->name('calendar.schedule');  //user_calendar schedule page
        Route::post('calendar/upload', 'storeSchedule')->name('calendar.upload');  //upload user_scheduled event(s)
        Route::get('calendar/view{scheduleId}/{link}', 'viewSchedules')->name('calendar.details');  //view user_calendar details page
        Route::post('calendar/edit', 'editSchedule')->name('calendar.edit');  //edit user_calendar details
        Route::get('calendar/delete', 'destroySchedule')->name('calendar.delete');  //delete user_calendar
        Route::get('calendar/destroy', 'destroySchedules')->name('calendar.destroy');  //delete user_calendar schedules

        /**-----------------------------------------------User Timetable Routes------------------------------------------------------------ */
        Route::get('timetable', 'userTimetable')->name('timetable');  //user_timetable page
        Route::post('timetable/upload', 'storeTimetable')->name('timetable.upload');  //upload user_timetable
        Route::get('timetable/view{resourceId}', 'viewTimetable')->name('timetable.details');  //view user_timetable details
        Route::post('timetable/edit', 'editTimetable')->name('timetable.edit');  //edit user_timetable details
        Route::get('timetable/delete', 'destroyTimetable')->name('timetable.delete');  //delete user_timetable
        
        Route::get('logout', 'logout')->name('logout');  //user_logout action
    });
});

