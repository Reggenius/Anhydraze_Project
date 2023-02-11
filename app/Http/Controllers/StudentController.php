<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\Student;


//use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\PasswordBroker;


class StudentController extends Controller
{
    
    /**
     * @return index.blade.php
     */
    public function homepage()
    {
        return view('index');
    }

    /**
     * @return signup.blade.php
     */
    public function signup()
    {
        return view('signup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response(user.login)
     */
    public function addUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_name'=>'required|string|unique:students',
            'user_email'=>'required|string|unique:students',
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()]
        ]);
        

        if($validate->fails())
        {
            session()->put('error', $validate->errors()->first());
            return redirect()->route('pages.signup');
        }
        $input = $validate->validated();
        
        $student = new Student;
        $student->user_name = $input['user_name'];
        $student->password = Hash::make($input['password']);
        $student->user_email = $input['user_email'];
        if($student->save())
        {
            session()->put('success', 'Sign-up successful!.');
            return redirect()->route('user.login');
        }
        else
        {
            session()->put('error', 'Sign-up unsuccessful, try again.');
            return back();
        }
    }

    /**
     * @return login.blade.php
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateLogin(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'password' => 'required|string',
        ]);

        if($validate->fails())
        {
            session()->put('error', $validate->errors()->first());
            return redirect()->route('user.login');
        }
        $input = $validate->validated();
        //Inserting a braces () after the userResource returns a joined version of the two tables
        //This technique won't work on echo see video
        $data = Student::where('user_name', $input['user_name'])
                            ->select('student_id')
                            ->first();

        //Authenticate customer 
        if(Auth::guard('student')->attempt($input)) {
            //Create login session
            $request->session()->regenerate();

            $data = Student::where('user_name', $input['user_name'])
                                ->select('student_id')
                                ->first();
            session()->put('success', 'Login successful!.');
            return redirect()->route('user.index', $data);
        }

        session()->put('error', 'Wrong email or password!');
        return redirect()->route('user.login');
    }

    /**
     * @return error404.blade.php
     */
    public function error404()
    {
        return view('error404');
    }

    
    /**
     * Create a session
     */
    public function createSession()
    {
        session()->put('error', 'Sorry, this functionality has not been enabled.');
        return back();
    }


/*----------------------------Forgot Password Functions Starts Here----------------------------------------*/
    /**
     * @return forgetPassword.blade.php
     */
    /*
     public function getForgetPassword()
    {
        //return view('forgetPassword');
    }
    */

    /**
     *
     * @return resetPassword.blade.php
     */
    /*
    public function resetPassword()
    {
        return view('resetPassword');
    }
    */

    /**
     * Update Password in database.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*
    public function email(Request $request)
    {
        //return view('forgetPassword');
        //dd($request);
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
     
        $data =  Password::RESET_LINK_SENT ? ['status' => __($status)] : ['email' => __($status)];

        return redirect()->route('user.login', $data);
    }
    */

   
/*----------------------------Forgot Password Functions Ends Here------------------------------------------*/

}