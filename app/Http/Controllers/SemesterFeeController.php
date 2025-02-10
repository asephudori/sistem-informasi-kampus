<?php

namespace App\Http\Controllers;

use App\Models\SemesterFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use Throwable;

class SemesterFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $page = $request->query('page', 1);

            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            $semesterFees = SemesterFee::with(['semester', 'user', 'transaction'])->paginate($perPage);

            return response()->json($semesterFees);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error retrieving semester fees', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'semester_id' => 'required|exists:semesters,id',
            'student_id' => 'required|exists:users,id',
            'transaction_id' => 'required|exists:transactions,id',
            'payment_status' => 'required|in:unpaid,awaiting_verification,paid',
            'payment_proof' => 'nullable|string', // Adjust validation as needed for file uploads
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $semesterFee = SemesterFee::create($request->all());
            return response()->json($semesterFee, 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error creating semester fee', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $semesterFee = SemesterFee::with(['semester', 'user', 'transaction'])->findOrFail($id);
            return response()->json($semesterFee, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Semester fee not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error fetching semester fee', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'semester_id' => 'exists:semesters,id',
            'student_id' => 'exists:users,id',
            'transaction_id' => 'exists:transactions,id',
            'payment_status' => 'in:unpaid,awaiting_verification,paid',
            'payment_proof' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $semesterFee = SemesterFee::findOrFail($id);
            $semesterFee->update($request->all());
            return response()->json($semesterFee, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Semester fee not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error updating semester fee', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $semesterFee = SemesterFee::findOrFail($id);
            $semesterFee->delete();
            return response()->json(['message' => 'Semester fee deleted'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Semester fee not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error deleting semester fee', 'error' => $e->getMessage()], 500);
        }
    }
}