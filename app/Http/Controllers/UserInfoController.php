<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Http\Resources\AdminResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\LecturerResource;
use Illuminate\Validation\ValidationException;

class UserInfoController extends Controller
{
    private function validateData(Request $request, $role) {
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

        return match ($role) {
            'admin' => $request->validate([
                'permission_role_id' => 'sometimes|exists:permission_roles,id',
                'name' => 'sometimes|max:255',
                'role' => 'sometimes|in:super admin,admin',
            ]),
            'student' => $request->validate([
                'advisory_class_id' => 'sometimes|exists:advisory_classes,id',
                'study_program_id' => 'sometimes|exists:study_programs,id',
                'name' => 'sometimes|max:255',
                'email' => 'sometimes|email|max:255',
                'phone' => 'sometimes|max:255',
                'birthplace' => 'sometimes|max:255',
                'birthdate' => 'sometimes|date',
                'home_address' => 'sometimes|max:255',
                'current_address' => 'sometimes|max:255',
                'home_city_district' => 'sometimes|max:255',
                'home_postal_code' => 'sometimes|max:255',
                'gender' => 'sometimes|in:male,female',
            ]),
            'lecturer' => $request->validate([
                'nidn' => 'sometimes|unique:lecturers,nidn',
                'name' => 'sometimes|max:255',
                'email' => 'sometimes|email|max:255',
                'phone' => 'sometimes|max:255',
                'address' => 'sometimes|max:255',
                'entry_date' => 'sometimes|date',
                'birthplace' => 'sometimes|max:255',
                'birthdate' => 'sometimes|date',
                'gender' => 'sometimes|in:male,female',
            ]),
        };
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        switch ($user->role()) {
            case 'admin':
                $result = Admin::findOrFail($user->id);
                return new AdminResource($result);
            case 'student':
                $result = Student::findOrFail($user->id);
                return new StudentResource($result);
            case 'lecturer':
                $result = Lecturer::findOrFail($user->id);
                return new LecturerResource($result);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();
            $validatedData = $this->validateData($request, $user->role());

            switch ($user->role()) {
                case 'admin':
                    $userInfo = Admin::findOrFail($user->id);
                    $userInfo->update($validatedData);
                    $result = new AdminResource($userInfo);
                    break;
                case 'student':
                    $userInfo = Student::findOrFail($user->id);
                    $userInfo->update($validatedData);
                    $result = new StudentResource($userInfo);
                    break;
                case 'lecturer':
                    $userInfo = Lecturer::findOrFail($user->id);
                    $userInfo->update($validatedData);
                    $result = new LecturerResource($userInfo);
                    break;
            }
            return response()->json(['message' => 'User info updated successfully', 'user-info' => $result]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user info', 'errors' => $e->getMessage()], 500);
        }
    }
}
