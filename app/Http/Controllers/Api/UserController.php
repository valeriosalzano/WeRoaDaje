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
        // CREDENTIALS CHECK
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        // CREATE TOKEN
        $token = $user->createToken('auth_token', [$user->role ? $user->role->name : 'subscriber'])->plainTextToken;

        $response = [
            'status' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'Logged in as ' . ($user->role ? $user->role->name : 'subscriber')
        ];

        return response($response, 201);
    }

    function destroy(Request $request)
    {
        // CREDENTIALS CHECK
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        // NO TOKEN FOUND
        if (!$user->tokens?->count()) return response([
            'status' => false,
            'message' => 'Already logged out'
        ], 400);

        // DELETE TOKEN
        $user->tokens()->delete();

        return response([
            'status' => true,
            'user' => $user,
            'message' => 'Logged out'
        ], 201);
    }
}
