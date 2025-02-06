<?php

namespace App\Http\Controllers;

use App\Models\StudyProgramLecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class StudyProgramLecturerController extends Controller
{
    // show all study program lectures
    public function index(Request $request)
    {
        try {
            $studyProgramLecturers = StudyProgramLecturer::with(['user', 'studyProgram'])->get();

            return response()->json(['data' => $studyProgramLecturers], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to fetch study program lecturers', 'error' => $e->getMessage()], 500);
        }
    }

    // create study program lectures
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'required|exists:users,id',
            'study_program_id' => 'required|exists:study_programs,id',
            'lecturer_position' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $studyProgramLecturer = StudyProgramLecturer::create($request->all());
            return response()->json(['data' => $studyProgramLecturer, 'message' => 'Study Program Lecturer created successfully'], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to create study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    // show study program lectures by id
    public function show(StudyProgramLecturer $studyProgramLecturer)
    {
        try {
            $studyProgramLecturer->load(['user', 'studyProgram']);
            return response()->json(['data' => $studyProgramLecturer], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Study Program Lecturer not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to fetch study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    // update study program lectures
    public function update(Request $request, StudyProgramLecturer $studyProgramLecturer)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_id' => 'exists:users,id',
            'study_program_id' => 'exists:study_programs,id',
            'lecturer_position' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $studyProgramLecturer->update($request->all());
            return response()->json(['data' => $studyProgramLecturer, 'message' => 'Study Program Lecturer updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Study Program Lecturer not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    // delete study program lectures
    public function destroy(StudyProgramLecturer $studyProgramLecturer)
    {
        try {
            $studyProgramLecturer->delete();
            return response()->json(['message' => 'Study Program Lecturer deleted successfully'], 204);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to delete study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    // show study program lectures by faculty
    public function lecturersByFaculty($facultyId)
    {
        try {
            $lecturers = StudyProgramLecturer::with(['user', 'studyProgram'])
                ->whereHas('studyProgram', function ($query) use ($facultyId) {
                    $query->where('faculty_id', $facultyId);
                })
                ->get();

            return response()->json(['data' => $lecturers], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to fetch lecturers by faculty', 'error' => $e->getMessage()], 500);
        }
    }
}