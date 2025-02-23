<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseController extends Controller
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
            'prerequisite_id' => 'sometimes|exists:courses,id',
        ];

        return $request->validate($rules);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courses = Course::all();
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve courses', 'errors' => $e->getMessage()], 500);;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);

            $course = Course::create(array_merge($validatedData));
            return response()->json(['message' => 'Course created successfully', 'course' => new CourseResource($course)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create course', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = Course::findOrFail($id);
            return new CourseResource($course);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve course', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $course = Course::findOrFail($id);
            $validatedData = $this->validatedData($request, true);

            if (!empty($validatedData['prerequisite_id'])) {
                if ($validatedData['prerequisite_id'] == $id) {
                    return response()->json(['message' => 'Course id cannot be prerequisite_id of it self'], 400);
                }
            }
            $course->update($validatedData);
            return response()->json(['message' => 'Course updated successfully', 'course' => new CourseResource($course)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update course', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();
            return response()->json(['message' => 'Course deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete course', 'errors' => $e->getMessage()], 500);
        }
    }
}
