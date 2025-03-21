<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use App\Http\Resources\AdminResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminController extends Controller
{
    use Loggable;
    private function validatedUserData(Request $request)
    {
        return $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'password' => 'required|min:8',
        ]);
    }

    private function validatedAdminData(Request $request, bool $useSometimes = false)
    {
        if (empty($request->all())) {
            $jsonData = trim($request->getContent());

            if (!empty($jsonData)) {
                $jsonData = preg_replace('/,\s*}/', '}', $jsonData);

                $decodedJson = json_decode($jsonData, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge($decodedJson);
                } else {
                    return response()->json([
                        'message' => 'Invalid JSON format',
                        'errors' => json_last_error_msg()
                    ], 400);
                }
            }
        }

        $rules = [
            'permission_role_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:permission_roles,id',
            'name' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'role' => ($useSometimes ? 'sometimes|' : 'required|') . 'in:super admin,admin',
        ];

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $admins = Admin::all();
            return AdminResource::collection($admins);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve admins', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedUserData = $this->validatedUserData($request);
            $validatedStudentData = $this->validatedAdminData($request);

            $user = User::create([
                'username' => $validatedUserData['username'],
                'password' => bcrypt($validatedUserData['password']),
            ]);
            $admin = Admin::create(array_merge(
                $validatedStudentData,
                ['user_id' => $user->id]
            ));
            $this->logActivity('New Admin Created!', 'Activity Detail: ' . $admin, "Create");
            return response()->json(['message' => 'Admin created successfully', 'admin' => new AdminResource($admin)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create admin', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            return new AdminResource($admin);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Admin not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve admin', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $validatedAdminData = $this->validatedAdminData($request, true);
            $admin->update($validatedAdminData);
            $this->logActivity('New Admin Updated!', 'Activity Detail: ' . $admin, "Update");
            return response()->json(['message' => 'Admin updated successfully', 'admin' => new AdminResource($admin)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Admin not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update admin', 'errors' => $e->getMessage()], 500);
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
            $this->logActivity('New Admin Deleted!', 'Activity Detail: ' . $user, "Delete");
            return response()->json(['message' => 'Admin deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Admin not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete admin', 'errors' => $e->getMessage()], 500);
        }
    }
}
