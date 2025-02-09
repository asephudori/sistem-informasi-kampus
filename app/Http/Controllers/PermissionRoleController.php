<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionRole;
use App\Http\Resources\PermissionRoleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRoleController extends Controller
{
    private function validatedData(Request $request)
    {
        return $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
