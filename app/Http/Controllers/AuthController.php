<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        //validation
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed",
        ]);

        //create user
        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["name"],
            "password" => Hash::make($validated["password"]),
        ]);
        dd($user);
        //assign roles
        $employee_role = Role::where("name", "employee")->first();
        $employee_role->user()->save($user);

        //create token
        $token = $user->createToken("api_token")->plainTextToken;

        //return response
        $response = [
            "user" => $user,
            "access_token" => $token,
        ];
        return response()->json($response, 201);
        // return $response;
    }

    public function login(Request $request)
    {
        //validation
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // attempt authentication
        if (Auth::attempt($validated))
        {
            //get user object
            $user = User::where("email", $validated["email"])->first();

            // create Token
            $token = $user->createToken("api_token")->plainTextToken;

        }
        else {

        }

        // if succeed
        //
        //return token response
    }
}
