<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\PermissionRole;
use App\Traits\Loggable;
use Illuminate\Validation\Rule;
use App\Http\Resources\PermissionRoleResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRoleController extends Controller
{
    use Loggable;
    private function validatedData(Request $request, bool $useSometimes = false, int $id = null)
    {
        // Jika request kosong, coba decode JSON dan merge ke request
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
            'up' => 'sometimes|array|nullable',
            'up.*' => 'integer|exists:permissions,id',
        ];

        if ($id !== null) {
            $rules['down'] = 'sometimes|array|nullable';
            $rules['down.*'] = [
                'integer',
                Rule::exists('permission_groups', 'permission_id')
                    ->where('permission_role_id', $id)
            ];
        }

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissionRoles = PermissionRole::all();
            return PermissionRoleResource::collection($permissionRoles);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve permission roles', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);
            $filteredData = Arr::except($validatedData, ['up', 'down']);

            $permissionRole = PermissionRole::create(array_merge($filteredData));

            $validatedData['up'] = array_unique($validatedData['up'] ?? []);
            if (!empty($validatedData['up'])) {
                $permissionRole->permissions()->attach($validatedData['up']); // Tambah permission
            }

            $this->logActivity(
                'New Permission Role Created!',
                'Activity Detail: ' . $permissionRole,
                "Create"
            );
            return response()->json(['message' => 'Permissoin role created successfully', 'permission_role' => new PermissionRoleResource($permissionRole)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create permission role', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $permissionRole = PermissionRole::findOrFail($id);
            return new PermissionRoleResource($permissionRole);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve permission role', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $permissionRole = PermissionRole::findOrFail($id);
            $validatedData = $this->validatedData($request, true, $id);
            $filteredData = Arr::except($validatedData, ['up', 'down']);

            $permissionRole->update($filteredData);

            $validatedData['up'] = array_unique($validatedData['up'] ?? []);
            $validatedData['down'] = array_unique($validatedData['down'] ?? []);

            $existingPermissions = $permissionRole->permissions()->pluck('permission_id')->toArray();
            $validatedData['up'] = array_diff($validatedData['up'], $existingPermissions);

            if (!empty($validatedData['up'])) {
                $permissionRole->permissions()->attach($validatedData['up']); // Tambah permission
            }

            if (!empty($validatedData['down'])) {
                $permissionRole->permissions()->detach($validatedData['down']); // Hapus permission
            }

            $this->logActivity(
                'New Permission Role Updated!',
                'Activity Detail: ' . $permissionRole,
                "Update"
            );
            return response()->json(['message' => 'Permission role updated successfully', 'permission_role' => new PermissionRoleResource($permissionRole)], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update permission role', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permissionRole = PermissionRole::findOrFail($id);
            $permissionRole->permissions()->detach();
            $permissionRole->delete();
            $this->logActivity(
                'New Permission Role Deleted!',
                'Activity Detail: ' . $permissionRole,
                "Delete"
            );
            return response()->json(['message' => 'Permission role deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete permission role', 'errors' => $e->getMessage()], 500);
        }
    }
}
