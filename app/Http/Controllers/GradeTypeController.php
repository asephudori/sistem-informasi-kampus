<?php

namespace App\Http\Controllers;

use App\Models\GradeType;
use Illuminate\Http\Request;
use App\Traits\Loggable;
use App\Http\Resources\GradeTypeResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GradeTypeController extends Controller
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
            'name' => ($useSometimes ? 'sometimes|' : 'required|') . 'max:255',
            'percentage' => ($useSometimes ? 'sometimes|' : 'required|') . 'integer',
        ];

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $gradeTypes = GradeType::all();
            return GradeTypeResource::collection($gradeTypes);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve grade types', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);

            $gradeType = GradeType::create(array_merge($validatedData));
            $this->logActivity('New Grade Format Created!', 'Activity Detail: ' . $gradeType, "Create");
            return response()->json(['message' => 'Grade type created successfully', 'grade_type' => new GradeTypeResource($gradeType)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create grade type', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $gradeType = GradeType::findOrFail($id);
            return new GradeTypeResource($gradeType);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade type not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve grade type', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $gradeType = GradeType::findOrFail($id);
            $validatedData = $this->validatedData($request, true);
            $gradeType->update($validatedData);
            $this->logActivity('New Grade Format Updated!', 'Activity Detail: ' . $gradeType, "Update");
            return response()->json(['message' => 'Grade type updated successfully', 'grade_type' => new GradeTypeResource($gradeType)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade type not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update grade type', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $gradeType = GradeType::findOrFail($id);
            $gradeType->delete();
            $this->logActivity('New Grade Format Deleted!', 'Activity Detail: ' . $gradeType, "Delete");
            return response()->json(['message' => 'Grade type deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade type not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete grade type', 'errors' => $e->getMessage()], 500);
        }
    }
}
