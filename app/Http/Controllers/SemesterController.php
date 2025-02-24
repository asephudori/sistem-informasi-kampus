<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\LearningClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class SemesterController extends Controller
{
    // Get all semesters
    public function index(): JsonResponse
    {
        try {
            $semesters = Semester::all();

            if ($semesters->isEmpty()) {
                return response()->json(['message' => 'No semesters found'], 404);
            }

            return response()->json($semesters);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching semesters', 'error' => $e->getMessage()], 500);
        }
    }

    // Store a new semester
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:10',
                'start_periode' => 'required|date',
                'end_periode' => 'required|date|after_or_equal:start_periode',
            ]);

            $semester = Semester::create($validated);
            return response()->json($semester, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the semester', 'error' => $e->getMessage()], 500);
        }
    }

    // Get a specific semester
    public function show($id): JsonResponse
    {
        try {
            $semester = Semester::find($id);
            if (!$semester) {
                return response()->json(['message' => 'Semester not found'], 404);
            }
            return response()->json($semester);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the semester', 'error' => $e->getMessage()], 500);
        }
    }

    // Update a semester
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $semester = Semester::find($id);
            if (!$semester) {
                return response()->json(['message' => 'Semester not found'], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:10',
                'start_periode' => 'sometimes|required|date',
                'end_periode' => 'sometimes|required|date|after_or_equal:start_periode',
            ]);

            $semester->update($validated);
            return response()->json($semester);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the semester', 'error' => $e->getMessage()], 500);
        }
    }

    // Delete a semester
    public function destroy($id): JsonResponse
    {
        try {
            $semester = Semester::find($id);
            if (!$semester) {
                return response()->json(['message' => 'Semester not found'], 404);
            }

            $semester->delete();

            return response()->json(['message' => 'Semester deleted successfully'], 200);

        } catch (QueryException $e) {
            if ($e->getCode() == '23000') { // Foreign key constraint violation code
                $relatedClasses = LearningClass::where('semester_id', $id)->get();

                return response()->json([
                    'message' => 'Semester cannot be deleted due to existing related data.',
                    'error' => 'Integrity constraint violation',
                    'related_classes' => $relatedClasses,
                ], 409); // 409 Conflict
            }

            return response()->json(['message' => 'An error occurred while deleting the semester', 'error' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the semester', 'error' => $e->getMessage()], 500);
        }
    }
}
