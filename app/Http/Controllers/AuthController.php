<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required'],
            'phone' => ['required','unique:users','regex:/^(?:\+254|0)[17]\d{8}$/'],
            'email' => ['required','unique:users', 'email'],
            'password' => ['required','min:8','confirmed'],
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => $validator->errors(),
            ], 401);
        }
        $data = $request->all();
        // Split the name into first and last name
        $nameParts = explode(" ", $data['name']);
        $data['firstName'] = $nameParts[0];
        $data['lastName'] = $nameParts[1];
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data)->assignRole('user');
        $token = $user->createToken('appToken')->accessToken;

        /*return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);*/
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->fullName,
                'phone' => $user->phone,
                'email' => $user->email
            ],
        ], 200);
    }

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();
            $token = $user->createToken('appToken')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->fullName,
                    'phone' => $user->phone,
                    'email' => $user->email
                ],
            ], 200);

            // return response()->json([
            //     'token' => $token,
            //     'user' => $user,
            // ], 200);
        }
        else{
            return response()->json([
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::user()){
            $user = Auth::user()->token();
            $user->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ], 200);
        }
        else{
            return response() -> json([
                'success' => false,
                'message' => 'unable to Logout'
            ]);
        }
    }

    public function user()
    {
        return response()->json([
            'user' => auth()->user()
        ], 200);
    }
}
