<?php

namespace App\Http\Controllers;

use App\Models\FacultyLecturer;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class FacultyLecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $facultyLecturers = FacultyLecturer::with(['lecturer', 'faculty'])->get();

            if ($facultyLecturers->isEmpty()) {
                return response()->json(['message' => 'No faculty lecturers found'], 404);
            }

            return response()->json($facultyLecturers);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching faculty lecturers', 'error' => $e->getMessage()], 500);
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
                'faculty_id' => 'required|exists:faculties,id',
                'lecturer_position' => 'required|string|max:255',
            ]);

            $facultyLecturer = FacultyLecturer::create($validated);
            return response()->json($facultyLecturer, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $facultyLecturer = FacultyLecturer::with(['user', 'faculty'])->findOrFail($id); // Use findOrFail

            return response()->json($facultyLecturer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Faculty lecturer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $facultyLecturer = FacultyLecturer::findOrFail($id);

            $validated = $request->validate([
                'lecturer_id' => 'sometimes|required|exists:lecturers,id',
                'faculty_id' => 'sometimes|required|exists:faculties,id',
                'lecturer_position' => 'sometimes|required|string|max:255',
            ]);

            $facultyLecturer->update($validated);
            return response()->json($facultyLecturer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Faculty lecturer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $facultyLecturer = FacultyLecturer::findOrFail($id);
            $facultyLecturer->delete();

            return response()->json(['message' => 'Faculty lecturer deleted successfully'], 200); // Or 204 No Content
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Faculty lecturer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the faculty lecturer', 'error' => $e->getMessage()], 500);
        }
    }
}