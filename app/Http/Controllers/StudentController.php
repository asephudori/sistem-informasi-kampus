<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    private function validatedUserData(Request $request)
    {
        return $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'password' => 'required|min:8',
        ]);
    }

    private function validatedStudentData(Request $request, bool $useSometimes = false)
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
            'advisory_class_id' => ($useSometimes ? 'sometimes|' : 'nullable|') . 'exists:advisory_classes,id',
            'study_program_id' => ($useSometimes ? 'sometimes|' : 'nullable|') . 'exists:study_programs,id',
            'name' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'email' => ($useSometimes ? 'sometimes|' : 'required|') . 'email|max:255',
            'phone' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'birthplace' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'birthdate' => ($useSometimes ? 'sometimes|' : 'required|') . 'date',
            'home_address' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'current_address' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'home_city_district' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'home_postal_code' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'gender' => ($useSometimes ? 'sometimes|' : 'required|') . 'in:male, female',
        ];

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $students = Student::orderBy('id', 'desc')->paginate(12);
            return StudentResource::collection($students);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve students', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedUserData = $this->validatedUserData($request);
            $validatedStudentData = $this->validatedStudentData($request);

            $user = User::create([
                'username' => $validatedUserData['username'],
                'password' => bcrypt($validatedUserData['password']),
            ]);
            $student = Student::create(array_merge(
                $validatedStudentData,
                [
                    'user_id' => $user->id,
                    'nim' => $user->id,
                ]
            ));
            return response()->json(['message' => 'Student created successfully', 'student' => new StudentResource($student)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create student', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $student = Student::findOrFail($id);
            return new StudentResource($student);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve student', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $student = Student::findOrFail($id);
            $validatedStudentData = $this->validatedStudentData($request, true);
            $student->update($validatedStudentData);
            return response()->json(['message' => 'Student updated successfully', 'student' => new StudentResource($student)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update student', 'errors' => $e->getMessage()], 500);
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
            return response()->json(['message' => 'Student deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete student', 'errors' => $e->getMessage()], 500);
        }
    }
}
