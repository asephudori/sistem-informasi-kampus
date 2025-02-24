<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Resources\ClassroomResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassroomController extends Controller
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
            'name' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
        ];

        return $request->validate($rules);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $classrooms = Classroom::all();
            return ClassroomResource::collection($classrooms);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve classrooms', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);

            $classroom = Classroom::create(array_merge($validatedData));
            return response()->json(['message' => 'Course created successfully', 'classroom' => new ClassroomResource($classroom)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create classroom', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            return new ClassroomResource($classroom);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Classroom not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve classroom', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            $validatedData = $this->validatedData($request, true);

            if (!empty($validatedData['prerequisite_id'])) {
                if ($validatedData['prerequisite_id'] == $id) {
                    return response()->json(['message' => 'Classroom id cannot be prerequisite_id of it self'], 400);
                }
            }
            $classroom->update($validatedData);
            return response()->json(['message' => 'Classroom updated successfully', 'classroom' => new ClassroomResource($classroom)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Classroom not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update classroom', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            $classroom->delete();
            return response()->json(['message' => 'Classroom deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Classroom not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete classroom', 'errors' => $e->getMessage()], 500);
        }
    }
}
