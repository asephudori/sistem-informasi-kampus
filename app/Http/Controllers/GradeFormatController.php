<?php

namespace App\Http\Controllers;

use App\Models\GradeFormat;
use Illuminate\Http\Request;
use App\Traits\Loggable;
use App\Http\Resources\GradeFormatResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GradeFormatController extends Controller
{
    use Loggable;
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
            'min_grade' => ($useSometimes ? 'sometimes|' : 'required|') . 'integer',
            'max_grade' => ($useSometimes ? 'sometimes|' : 'required|') . 'integer',
            'alphabetical_grade' => ($useSometimes ? 'sometimes|' : 'required|') . 'string',
        ];

        return $request->validate($rules);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $gradeFormats = GradeFormat::all();
            return GradeFormatResource::collection($gradeFormats);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve grade formats', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // try {
        //     $validatedData = $this->validatedData($request);

        //     $gradeFormat = GradeFormat::create(array_merge($validatedData));
            // $this->logActivity('New Grade Format Created!', 'Activity Detail: ' . $gradeFormat, "Create");
        //     return response()->json(['message' => 'Grade format created successfully', 'grade_format' => new GradeFormatResource($gradeFormat)], 201);
        // } catch (ValidationException $e) {
        //     return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        // } catch (\Exception $e) {
        //     return response()->json(['message' => 'Failed to create grade format', 'errors' => $e->getMessage()], 500);
        // }
        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $gradeFormat = GradeFormat::findOrFail($id);
            return new GradeFormatResource($gradeFormat);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade format not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve grade format', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $gradeFormat = GradeFormat::findOrFail($id);
            $validatedData = $this->validatedData($request, true);
            $gradeFormat->update($validatedData);
            $this->logActivity('New Grade Format Updated!', 'Activity Detail: ' . $gradeFormat, "Update");
            return response()->json(['message' => 'Grade format updated successfully', 'grade_format' => new GradeFormatResource($gradeFormat)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade format not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update grade format', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $gradeFormat = GradeFormat::findOrFail($id);
            $gradeFormat->delete();
            $this->logActivity('New Grade Format Deleted!', 'Activity Detail: ' . $gradeFormat, "Delete");
            return response()->json(['message' => 'Grade format deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade format not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete grade', 'errors' => $e->getMessage()], 500);
        }
    }
}
