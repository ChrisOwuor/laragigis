<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required'
        ]);
        if ($validated->fails()) {
            return response()->json(["status" => "false", "errors" => $validated->errors()]);
        }
        $user = User::create($validated->validated());

        $token = $user->createToken("API TOKEN")->plainTextToken;

        return response()->json(compact('token'), 200);
    }
    public function login(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validated->fails()) {
                return response()->json(["status" => "false", "errors" => $validated->errors()]);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json(["status" => "false", "errors" => "Invalid credentials"]);
            }
            $user = User::where('email', $validated->safe()->only(['email']))->first();
            $token = $user->createToken('API TOKEN')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Throwable $th) {
            return response()->json(["status" => "false", "errors" => $th->getMessage()]);
        }
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return response()->json(["status" => "success", "user" => $user]);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(["status" => "success", "message" => "successfully logged out"]);
    }
}
