<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
        ]);

            $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
            ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                    'status' => true,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid login details'
                    ], 401);
            }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        if($user->status == 0){
            return response()->json([
                'status' => false,
                'message' => 'User has been Banned'
            ]);
        }

        return response()->json([
                'status' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'data' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $tokenId = $request->user()->currentAccessToken()->id;
        $token = $user->tokens()->where('id', $tokenId);
        if ($token == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Token ID',
            ]);
        }

        $token->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout Succesfully',
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            "id"=> $request->user()->id ,
            "name"=> $request->user()->name,
            "email"=> $request->user()->email,
            "status"=> $request->user()->status,
            "type_id"=> $request->user()->userType->name,
            "about"=> $request->user()->about,
            "image_path"=> $request->user()->image_path,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->name != null) {
            $user->name = $request->name;
        }
        if ($request->about != null) {
            $user->about = $request->about;
        }
        if ($request->image_path != null) {
            $user->image_path = $request->image_path;
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile Edited'
        ]);
    }

    public function cekStatus(Request $request)
    {

        $user = $request->user();
        if($user->status == 0){
            return response()->json([
                'status' => false,
                'message' => 'Hai '.$user->name.' ,
Your Account has been Banned

For more information you can contact us at skethlinks.help@gmail.com'
            ]);
        }else {
            return response()->json([
                'status' => true,
                'message' => 'User Active'
            ]);
        }

    }

}
