<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $relatedModel = match ($user->role()) {
            'student' => $user->student,
            'lecturer' => $user->lecturer,
            'admin' => $user->admin,
            default => null,
        };

        $name = $relatedModel?->name ?? null;
        $email = in_array($user->role(), ['student', 'lecturer']) ? $relatedModel?->email ?? null : null;
        $admin_role = $user->role() === 'admin' ? $relatedModel->role : null;
        $admin_permission_role = $user->role() === 'admin' ? $relatedModel->permissionRole : null;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'role' => $user->role(),
                'name' => $name,
                'email' => $email,
                ...( $user->role() === 'admin' ? [
                    'admin_role' => $admin_role,
                    'admin_permission_role' => $admin_permission_role,
                ] : [] )
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
