<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentCollection;
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

    private function validatedStudentData(Request $request)
    {
        return $request->validate([
            'advisory_class_id' => 'nullable|exists:advisory_classes,id',
            'study_program_id' => 'nullable|exists:study_programs,id',
            'nim' => 'required|unique:students,nim|max:255',  // Need discussion
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'birthplace' => 'required|max:255',
            'birthdate' => 'required|date',
            'home_address' => 'required|max:255',
            'current_address' => 'required|max:255',
            'home_city_district' => 'required|max:255',
            'home_postal_code' => 'required|max:255',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $students = Student::all();
            return new StudentCollection($students);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve students', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                ['user_id' => $user->id]
            ));
            return response()->json(['message' => 'Student created successfully', 'student' => $student]);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $student = Student::findOrFail($id);

            $validatedStudentData = $this->validatedStudentData($request);

            $student->update($validatedStudentData);
            return response()->json(['message' => 'Student updated successfully', 'student' => $student]);
        } catch (ModelNotFoundException $e) {
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
            $student = Student::findOrFail($id);
            $student->delete();
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
