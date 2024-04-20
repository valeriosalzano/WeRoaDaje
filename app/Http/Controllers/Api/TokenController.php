<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{

    function store(Request $request)
    {
        // CREDENTIALS CHECK
        $user = User::with('role')->where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        if($user->tokens->count()) $user->tokens()->delete();
        // CREATE TOKEN
        $token = $user->createToken('auth_token', [$user->role ? $user->role->name : 'subscriber'])->plainTextToken;


        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'Logged in as ' . ($user->role ? $user->role->name : 'subscriber')
        ], 201);
    }

    function destroy(Request $request)
    {
        // CREDENTIALS CHECK
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        // NO TOKEN FOUND
        if (!$user->tokens?->count()) return response()->json([
            'status' => false,
            'message' => 'Already logged out'
        ], 404);

        // DELETE TOKEN
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out'
        ], 204);
    }
}
