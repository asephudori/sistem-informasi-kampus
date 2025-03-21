<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lecturer;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\LecturerResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LecturerController extends Controller
{
    use Loggable;
    private function validatedUserData(Request $request)
    {
        return $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'password' => 'required|min:8',
        ]);
    }

    private function validatedLecturerData(Request $request, bool $useSometimes = false)
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
            'nidn' => ($useSometimes ? 'sometimes|' : 'nullable|') . 'unique:lecturers,nidn',
            'name' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'email' => ($useSometimes ? 'sometimes|' : 'required|') . 'email|max:255',
            'phone' => ($useSometimes ? 'sometimes|' : 'nullable|') . 'max:255',
            'address' => ($useSometimes ? 'sometimes|' : 'nullable|') . 'max:255',
            'entry_date' => ($useSometimes ? 'sometimes|' : 'required|') . 'date',
            'birthplace' => ($useSometimes ? 'sometimes|' : 'nullable|') . 'max:255',
            'birthdate' => ($useSometimes ? 'sometimes|' : 'nullable|') . 'date',
            'gender' => ($useSometimes ? 'sometimes|' : 'required|') . 'in:male,female',
        ];

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $lecturers = Lecturer::orderBy('user_id', 'desc')->paginate(12);
            return LecturerResource::collection($lecturers);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve lecturers', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedUserData = $this->validatedUserData($request);
            $validatedLecturerData = $this->validatedLecturerData($request);

            $user = User::create([
                'username' => $validatedUserData['username'],
                'password' => bcrypt($validatedUserData['password']),
            ]);
            $lecturer = Lecturer::create(array_merge(
                $validatedLecturerData,
                ['user_id' => $user->id]
            ));
            $this->logActivity('New Lecturer Created!', 'Activity Detail: ' . $lecturer, "Create");
            return response()->json(['message' => 'Lecturer created successfully', 'lecturer' => new LecturerResource($lecturer)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create lecturer', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $lecturer = Lecturer::findOrFail($id);
            return new LecturerResource($lecturer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lecturer not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $lecturer = Lecturer::findOrFail($id);
            $validatedLecturerData = $this->validatedLecturerData($request, true);
            $lecturer->update($validatedLecturerData);
            $this->logActivity('New Lecturer Updated!', 'Activity Detail: ' . $lecturer, "Update");
            return response()->json(['message' => 'Lecturer updated successfully', 'lecturer' => new LecturerResource($lecturer)], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lecturer not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update lecturer', 'errors' => $e->getMessage()], 500);
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
            $this->logActivity('New Lecturer Deleted!', 'Activity Detail: ' . $user, "Delete");
            return response()->json(['message' => 'Lecturer deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lecturer not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete lecturer', 'errors' => $e->getMessage()], 500);
        }
    }
}
