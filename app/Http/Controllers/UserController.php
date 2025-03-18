<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use Loggable;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::orderBy('id', 'desc')->paginate(12);
            return UserResource::collection($users);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve users', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|unique:users,username|max:255',
                'password' => 'required|min:8',
            ]);
            $user = User::create([
                'username' => $validatedData['username'],
                'password' => bcrypt($validatedData['password']),
            ]);

            $this->logActivity('New User Created!', 'Activity Detail: ' . $user, "Create");
            return response()->json(['message' => 'User created successfully', 'user' => new UserResource($user)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|unique:users,username|max:255',
            ]);

            $user = User::findOrFail($id);
            $user->username = $validatedData['username'];
            $user->save();

            $this->logActivity('New User Updated!', 'Activity Detail: ' . $user, "Update");
            return response()->json(['message' => 'User updated successfully', 'user' => new UserResource($user)]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            $this->logActivity('New User Deleted!', 'Activity Detail: ' . $user, "Delete");
            return response()->json(['message' => 'User deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
