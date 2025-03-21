<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StudyProgramController extends Controller
{
    use Loggable;
    /**
     * Display a listing of the study programs.
     */
    public function index(): JsonResponse
    {
        try {
            $studyPrograms = StudyProgram::with('faculty')->get();

            if ($studyPrograms->isEmpty()) {
                return response()->json(['message' => 'No study programs found'], 404);
            }

            return response()->json($studyPrograms);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching study programs', 'error' => $e->getMessage()], 500);
        }
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

            $faculty = Faculty::find($validated['faculty_id']);
            $facultyName = $faculty->name;

            $studyProgram = StudyProgram::create($validated);
            $this->logActivity(
                'New Study Program Created!',
                'Activity Detail: ' . $studyProgram,
                "Create"
            );
            return response()->json($studyProgram, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the study program', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified study program.
     */
    public function show(StudyProgram $studyProgram): JsonResponse
    {
        try {
            // Laravel's route model binding will automatically return 404 if not found
            return response()->json($studyProgram->load('faculty'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the study program', 'error' => $e->getMessage()], 500);
        }
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


            $faculty = Faculty::find($validated['faculty_id']);
            $facultyName = $faculty->name;

            $studyProgram->update($validated);
            $this->logActivity(
                'New Study Program Updated!',
                'Activity Detail: ' . $studyProgram,
                "Update"
            );
            return response()->json($studyProgram);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the study program', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified study program from storage.
     */
    public function destroy(StudyProgram $studyProgram): JsonResponse
    {
        try {
            $studyProgram->delete();
            $this->logActivity(
                'New Study Program Deleted!',
                'Activity Detail: ' . $studyProgram,
                "Delete"
            );
            return response()->json(['message' => 'Study program deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the study program', 'error' => $e->getMessage()], 500);
        }
    }
}
