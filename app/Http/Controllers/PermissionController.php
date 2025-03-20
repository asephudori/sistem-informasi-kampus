<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Traits\Loggable;
use App\Http\Resources\PermissionResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends Controller
{
    use Loggable;
    private function validatedData(Request $request, bool $useSometimes = false)
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
            'name' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'description' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
        ];

        return $request->validate($rules);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = Permission::all();
            return PermissionResource::collection($permissions);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve permissions', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);

            $permission = Permission::create(array_merge($validatedData));
            $this->logActivity(
                'New Permission Created!',
                'Activity Detail: ' . $permission,
                "Create"
            );
            return response()->json(['message' => 'Permission created successfully', 'permission' => new PermissionResource($permission)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create permission', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return new PermissionResource($permission);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve permission', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $validatedData = $this->validatedData($request, true);
            $permission->update($validatedData);
            $this->logActivity(
                'New Permission Updated!',
                'Activity Detail: ' . $permission,
                "Update"
            );
            return response()->json(['message' => 'Permission updated successfully', 'permission' => new PermissionResource($permission)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update permission', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->permissionRoles()->detach();
            $permission->delete();
            $this->logActivity(
                'New Permission Deleted!',
                'Activity Detail: ' . $permission,
                "Delete"
            );
            return response()->json(['message' => 'Permission deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete permission', 'errors' => $e->getMessage()], 500);
        }
    }
}
