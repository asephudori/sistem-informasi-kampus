<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningClass;
use App\Http\Resources\LearningClassResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LearningClassController extends Controller
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
            'lecturer_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:lecturers,user_id',
            'course_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:courses,id',
            'semester_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:semesters,id',
        ];

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $learningClasses = LearningClass::all();
            return LearningClassResource::collection($learningClasses);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve learning classes', 'errors' => $e->getMessage()], 500);;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);

            $learningClass = LearningClass::create(array_merge($validatedData));
            return response()->json(['message' => 'Learning class created successfully', 'learning_class' => new LearningClassResource($learningClass)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create learning class', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $learningClass = LearningClass::findOrFail($id);
            return new LearningClassResource($learningClass);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'LearningClass not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve learning classe', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $learningClass = LearningClass::findOrFail($id);
            $validatedData = $this->validatedData($request, true);
            $learningClass->update($validatedData);
            return response()->json(['message' => 'Learning class updated successfully', 'learning_class' => new LearningClassResource($learningClass)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Learning class not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update learning class', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $learningClass = LearningClass::findOrFail($id);
            $learningClass->delete();
            return response()->json(['message' => 'Learning class deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Learning class not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete learning class', 'errors' => $e->getMessage()], 500);
        }
    }
}
