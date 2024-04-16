<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('auth_token', [$user->role ? $user->role->name : 'subscriber'])->plainTextToken;

        $response = [
            'status' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'Logged in as '.($user->role ? $user->role->name : 'subscriber')
        ];

        return response($response, 201);
    }

    function destroy(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        if(!$user->tokens?->count()) return response(['status'=>false,'message'=>'Already logged out'],400);

        $user->tokens()->delete();

        $response = [
            'status' => true,
            'user' => $user,
            'message' => 'Logged out'
        ];

        return response($response, 201);
    }
}
