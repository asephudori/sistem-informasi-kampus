<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class TransactionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transactionCategories = TransactionCategory::all();
            return response()->json(['data' => $transactionCategories], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve transaction categories.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:operating_revenue,non_operating_revenue,operating_expense,non_operating_expense',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $transactionCategory = TransactionCategory::create($request->all());
            return response()->json(['data' => $transactionCategory, 'message' => 'Transaction category created successfully.'], 201);
        } catch (QueryException $e) {
            // Handle database-specific errors (e.g., unique constraint violations)
             return response()->json(['message' => 'Failed to create transaction category. Database error.', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create transaction category.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionCategory $transactionCategory)
    {
        try {
            return response()->json(['data' => $transactionCategory], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve transaction category.', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionCategory $transactionCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:operating_revenue,non_operating_revenue,operating_expense,non_operating_expense',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $transactionCategory->update($request->all());
            return response()->json(['data' => $transactionCategory, 'message' => 'Transaction category updated successfully.'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to update transaction category. Database error.', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update transaction category.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionCategory $transactionCategory)
    {
        try {
            $transactionCategory->delete();
            return response()->json(['message' => 'Transaction category deleted successfully.'], 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete transaction category.', 'error' => $e->getMessage()], 500);
        }
    }
}