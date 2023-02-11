<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //Get the logged in user
        $user = auth('api')->user();
        $headers = [
            'Authorization' => 'Bearer '.$token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return response()->json($user, 200, $headers);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        //Get the logged in user
        return response()->json(
            auth('api')->user(), 
            200
        );
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        //Get the logged in user
        $user = auth('api')->user();
        $token = auth()->refresh();

        $headers = [
            'Authorization' => 'Bearer '.$token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return response()->json($user, 200, $headers);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out']);   //try to put 200
    }
}
