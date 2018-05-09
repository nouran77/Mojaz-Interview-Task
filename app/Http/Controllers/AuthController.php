<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * API Register new User Account
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');

        $user = new User([
            'username' => $username,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        $credentials = [
            'username' => $username,
            'password' => $password
        ];

        if ($user->save()) {
            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'message' => 'Username or Password are incorrect',
                    ], 404);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'message' => 'failed_to_create_token',
                ], 404);
            }
            $user->login = [
                'href' => 'api/login',
                'method' => 'POST',
                'params' => 'username, password'
            ];
            $response = [
                'message' => 'User is Created',
                'user' => $user,
                'token' => $token
            ];
            return response()->json($response, 201);
        }
        $response = [
            'message' => 'An error occurred'
        ];
        return response()->json($response, 404);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($user = User::where('username', $username)->first()) {
            $credentials = [
                'username' => $username,
                'password' => $password
            ];

            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'message' => 'Username or Password are incorrect',
                    ], 404);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'message' => 'failed_to_create_token',
                ], 404);
            }
            $response = [
                'message' => 'User Login',
                'user' => $user,
                'token' => $token
            ];
            return response()->json($response, 201);
        }
        $response = [
            'message' => 'An error occurred'
        ];
        return response()->json($response, 404);
    }

}
