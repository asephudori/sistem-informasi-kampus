<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StudyProgramController extends Controller
{
    /**
     * Display a listing of the study programs.
     */
    public function index(): JsonResponse
    {
        $studyPrograms = StudyProgram::with('faculty')->get();

        // Check if no study programs are found
        if ($studyPrograms->isEmpty()) {
            return response()->json(['message' => 'No study programs found'], 404);
        }
        return response()->json($studyPrograms);
    }

    /**
     * Store a newly created study program in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'faculty_id' => 'required|exists:faculties,id',
                'name' => 'required|string|max:50|unique:study_programs,name'
            ]);

            $studyProgram = StudyProgram::create($validated);
            return response()->json($studyProgram, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified study program.
     */
    public function show(StudyProgram $studyProgram): JsonResponse
    {
        // Check if the study program exists
        if (!$studyProgram) {
            return response()->json(['message' => 'Study program not found'], 404);
        }

        return response()->json($studyProgram->load('faculty'));
    }

    /**
     * Update the specified study program in storage.
     */
    public function update(Request $request, StudyProgram $studyProgram): JsonResponse
    {
        try {
            $validated = $request->validate([
                'faculty_id' => 'required|exists:faculties,id',
                'name' => 'required|string|max:50|unique:study_programs,name,' . $studyProgram->id
            ]);

            $studyProgram->update($validated);
            return response()->json($studyProgram);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Remove the specified study program from storage.
     */
    public function destroy(StudyProgram $studyProgram): JsonResponse
    {
        // Check if study program exists
        if (!$studyProgram) {
            return response()->json(['message' => 'Study program not found'], 404);
        }

        $studyProgram->delete();
        return response()->json(['message' => 'Study program deleted successfully']);
    }
}
