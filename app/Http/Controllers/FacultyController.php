<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacultyController extends Controller
{
    // Get all faculties
    public function index(): JsonResponse
    {
        try {
            $faculties = Faculty::all();

            if ($faculties->isEmpty()) {
                return response()->json(['message' => 'No faculties found'], 404);
            }

            return response()->json($faculties);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching faculties', 'error' => $e->getMessage()], 500);
        }
    }

    // Store a new faculty
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50|unique:faculties'
            ]);

            $faculty = Faculty::create($validated);
            return response()->json($faculty, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the faculty', 'error' => $e->getMessage()], 500);
        }
    }

    // Display the specified faculty.
    public function show($id): JsonResponse
    {
        try {
            $faculty = Faculty::find($id);

            if (!$faculty) {
                return response()->json(['message' => 'Faculty not found'], 404);
            }

            return response()->json($faculty);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the faculty', 'error' => $e->getMessage()], 500);
        }
    }

    // Update the specified faculty in storage.
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $faculty = Faculty::find($id);

            if (!$faculty) {
                return response()->json(['message' => 'Faculty not found'], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:50|unique:faculties,name,' . $id
            ]);

            $faculty->update($validated);
            return response()->json($faculty);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the faculty', 'error' => $e->getMessage()], 500);
        }
    }

    // Remove the specified faculty from storage.
    public function destroy($id): JsonResponse
    {
        try {
            $faculty = Faculty::find($id);

            if (!$faculty) {
                return response()->json(['message' => 'Faculty not found'], 404);
            }

            $faculty->delete();
            return response()->json(['message' => 'Faculty deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the faculty', 'error' => $e->getMessage()], 500);
        }
    }
}
