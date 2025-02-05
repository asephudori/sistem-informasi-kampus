<?php

namespace App\Http\Controllers;

use App\Models\StudyProgramLecturer;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class StudyProgramLecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $studyProgramLecturers = StudyProgramLecturer::with(['lecturer', 'studyProgram'])->get();

            if ($studyProgramLecturers->isEmpty()) {
                return response()->json(['message' => 'No study program lecturers found'], 404);
            }

            return response()->json($studyProgramLecturers);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching study program lecturers', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'lecturer_id' => 'required|exists:lecturers,id',
                'study_program_id' => 'required|exists:study_programs,id',
                'lecturer_position' => 'required|string|max:255',
            ]);

            $studyProgramLecturer = StudyProgramLecturer::create($validated);
            return response()->json($studyProgramLecturer, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $studyProgramLecturer = StudyProgramLecturer::with(['lecturer', 'studyProgram'])->findOrFail($id);

            return response()->json($studyProgramLecturer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Study program lecturer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $studyProgramLecturer = StudyProgramLecturer::findOrFail($id);

            $validated = $request->validate([
                'lecturer_id' => 'sometimes|required|exists:lecturers,id',
                'study_program_id' => 'sometimes|required|exists:study_programs,id',
                'lecturer_position' => 'sometimes|required|string|max:255',
            ]);

            $studyProgramLecturer->update($validated);
            return response()->json($studyProgramLecturer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Study program lecturer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $studyProgramLecturer = StudyProgramLecturer::findOrFail($id);
            $studyProgramLecturer->delete();

            return response()->json(['message' => 'Study program lecturer deleted successfully'], 200); // Or 204 No Content
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Study program lecturer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the study program lecturer', 'error' => $e->getMessage()], 500);
        }
    }
}