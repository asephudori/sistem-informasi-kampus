<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdvisoryClass;
use App\Traits\Loggable;
use App\Http\Resources\AdvisoryClassResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdvisoryClassController extends Controller
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
            'lecturer_id' => ($useSometimes ? 'sometimes|' : 'required|') . 'exists:lecturers,user_id',
            'class_year' => ($useSometimes ? 'sometimes|' : 'required|') . 'date_format:Y',
        ];

        return $request->validate($rules);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $advisoryClasses = AdvisoryClass::orderBy('id', 'desc')->paginate(12);
            return AdvisoryClassResource::collection($advisoryClasses);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve advisory classes', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validatedData($request);

            $advisoryClass = AdvisoryClass::create(array_merge($validatedData));
            $this->logActivity('New Advisory Class Created!', 'Activity Detail: ' . $advisoryClass, "Create");
            return response()->json(['message' => 'Advisory Class created successfully', 'advisory_class' => new AdvisoryClassResource($advisoryClass)], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create advisory class', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $advisoryClass = AdvisoryClass::findOrFail($id);
            return new AdvisoryClassResource($advisoryClass);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Advisory class not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve advisory class', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $advisoryClass = AdvisoryClass::findOrFail($id);
            $validatedData = $this->validatedData($request, true);
            $advisoryClass->update($validatedData);
            $this->logActivity('New Advisory Class Updated!', 'Activity Detail: ' . $advisoryClass, "Update");
            return response()->json(['message' => 'Advisory class updated successfully', 'advisory class' => new AdvisoryClassResource($advisoryClass)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Advisory class not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update advisory class', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $advisoryClass = AdvisoryClass::findOrFail($id);
            $advisoryClass->delete();
            $this->logActivity('New Advisory Class Deleted!', 'Activity Detail: ' . $advisoryClass, "Delete");
            return response()->json(['message' => 'Advisory class deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Advisory class not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete advisory class', 'errors' => $e->getMessage()], 500);
        }
    }
}
