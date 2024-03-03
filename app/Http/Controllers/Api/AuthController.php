<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function register(Request $request)
    {
        try {
            // Validate
            $validate = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'username' => 'required|unique:users,username',
                    'password' => 'required|confirmed',
                    'role' => 'required|in:owner,manager,cashier'
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password)
            ]);

            $user->assignRole($request->role);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data' => ['token' => $user->createToken("API TOKEN")->plainTextToken]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something whe to wrong please try Agin later',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'username' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['username', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Fail to authidicate',
                    'errors' => 'Username & Password does not match with our record.'
                ], 401);
            }

            $user = User::where('username', $request->username)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'data' => ['token' => $user->createToken("API TOKEN")->plainTextToken]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something whe to wrong please try Agin later',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'logged out',
                'data' => []
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something whe to wrong please try Agin later',
                'errors' => $th->getMessage()
            ], 500);
        }
       
    }
}