<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class ApiController extends Controller
{
    // Register Api (Columns names: name, email, password, password_confirmation).
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);
        User::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.'
        ]);
    }
    // Login Api
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password'=> 'required'
        ]);
        // Checking Whether Email Exists or Not
        $user = User::where('email', $request->email)->first();
        // Checking Whether Password Exists or Not
        if(!empty($user)){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken('myToken')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'Login Successful',
                    'token' => $token
                ]);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'Password is incorrect.'
                ]);
            }
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Email does not exist.'
            ]);

        }


    }
    // Profile Api
    public function profile(Request $request){
        $userData = auth()->user();
        return response()->json([
            'status' => true,
            'message' => 'User Profile Data',
            'data' => $userData,
            'id' => auth()->user()->id
        ]);


    }
    // Logout Api
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logout Successfully'
        ]);

    }
}
