<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Resources\GradeResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GradeController extends Controller
{
    private function validatedData(Request $request, bool $useSometimes = false)
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
            'student_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:students,user_id',
            'class_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:classes,id',
            'grade_type_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:grade_types,id',
            'grade' => ($useSometimes ? 'sometimes|' : 'required|') . 'integer',
        ];

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->query('student_id')) {
                $grades = Grade::where('student_id', $request->query('student_id'))
                               ->orderBy('id', 'desc')
                               ->paginate(12);
            } else {
                $grades = Grade::orderBy('id', 'desc')->paginate(12);
            }
            return GradeResource::collection($grades);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve grades', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);

            $grade = Grade::create(array_merge($validatedData));
            return response()->json(['message' => 'Grade created successfully', 'grade' => new GradeResource($grade)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create grade', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $grade = Grade::findOrFail($id);
            return new GradeResource($grade);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve grade', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $grade = Grade::findOrFail($id);
            $validatedData = $this->validatedData($request, true);
            $grade->update($validatedData);
            return response()->json(['message' => 'Grade updated successfully', 'grade' => new GradeResource($grade)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update grade', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $grade = Grade::findOrFail($id);
            $grade->delete();
            return response()->json(['message' => 'Grade deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete grade', 'errors' => $e->getMessage()], 500);
        }
    }
}
