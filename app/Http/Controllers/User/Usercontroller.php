<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Usercontroller extends Controller
{
    public function createUser(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ]);
        }

        // Create user
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        // Create and return token
        $token = $user->createToken('token')->plainTextToken;

        return response([
            'status' => 'success',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {


        $data = request()->all();
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ],422);
        }
        $email = request('email');
        $password = request('password');
        $user = User::where('email', $email)->get()->first();

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $token = $user->createToken('token')->plainTextToken;

            return response([
                'status' => 'success',
                'token' => $token,
                'user' => request()->user()
            ]);
        }
        else{

            return response([
                'status' => 'failed',
                'message' => 'Enter correct details',
            ]);
        }
    }
    public function auth(){
        if (Auth::check()) {
            return response()->json(['authenticated' => true]);
        } else {
            return response()->json(['authenticated' => false]);
        }
    }
}
