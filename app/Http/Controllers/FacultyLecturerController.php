<?php

namespace App\Http\Controllers;

use App\Models\FacultyLecturer;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class FacultyLecturerController extends Controller
{
    use Loggable;
    // show all faculty lecturers
    public function index(Request $request)
    {
        try {
            $facultyLecturers = FacultyLecturer::with(['user', 'faculty'])->get();
            return response()->json(['data' => $facultyLecturers], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to fetch faculty lecturers', 'error' => $e->getMessage()], 500);
        }
    }

    // create faculty lecturers
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'required|exists:users,id',
            'faculty_id' => 'required|exists:faculties,id',
            'lecturer_position' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $facultyLecturer = FacultyLecturer::create($request->all());
            $this->logActivity('New Faculty Lecturer Created!', 'Activity Detail: ' . $facultyLecturer, "Create");
            return response()->json(['data' => $facultyLecturer, 'message' => 'Faculty Lecturer created successfully'], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to create faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    // show faculty lecturer by id
    public function show(FacultyLecturer $facultyLecturer)
    {
        try {
            $facultyLecturer->load(['user', 'faculty']);
            return response()->json(['data' => $facultyLecturer], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Faculty Lecturer not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to fetch faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    // update faculty lecturers
    public function update(Request $request, FacultyLecturer $facultyLecturer)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'exists:users,id',
            'faculty_id' => 'exists:faculties,id',
            'lecturer_position' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $facultyLecturer->update($request->all());
            $this->logActivity('New Faculty Lecturer Updated!', 'Activity Detail: ' . $facultyLecturer, "Update");
            return response()->json(['data' => $facultyLecturer, 'message' => 'Faculty Lecturer updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Faculty Lecturer not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    // delete faculty lecturers
    public function destroy(FacultyLecturer $facultyLecturer)
    {
        try {
            $facultyLecturer->delete();
            $this->logActivity('New Faculty Lecturer Deleted!', 'Activity Detail: ' . $facultyLecturer, "Delete");
            return response()->json(['message' => 'Faculty Lecturer deleted successfully'], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to delete faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }
}