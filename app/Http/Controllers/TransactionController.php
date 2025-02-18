<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'transaction_category_id' => 'required|exists:transaction_categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'proof' => 'nullable|string', 
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Database Transaction
        DB::beginTransaction();
        try {
            $transaction = new Transaction();
            $transaction->transaction_category_id = $request->transaction_category_id;
            // $transaction->admin_id = auth()->user()->id;
            $transaction->type = $request->type;
            $transaction->amount = $request->amount;
            $transaction->description = $request->description;
            $transaction->proof = $request->proof; 
            $transaction->date = $request->date;
            $transaction->verification_status = 'pending'; 
            $transaction->save();

            DB::commit();

            return response()->json(['message' => 'Transaction created successfully', 'data' => $transaction], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction creation failed', 'error' => $e->getMessage()], 500); // Log the error for debugging
        }
    }

    
    public function transactionsByCategory($transaction_category_id)
    {
        try {
            $transactions = Transaction::where('transaction_category_id', $transaction_category_id)
                                    ->paginate(10); // Or get(), depending on your needs

            if ($transactions->isEmpty()) {
                return response()->json(['message' => 'No transactions found for this category.'], 200); // Or 404 if you prefer
            }

            return response()->json(['data' => $transactions], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve transactions.', 'error' => $e->getMessage()], 500);
        }
    }


    public function transactionsByCategoryReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'transaction_category_id' => 'required|exists:transaction_categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $transaction_category_id = $request->input('transaction_category_id');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $transactions = Transaction::where('transaction_category_id', $transaction_category_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->get();

            if ($transactions->isEmpty()) {
                return response()->json(['message' => 'No transactions found for this category within the specified date range.'], 200); // Or 404
            }

            return response()->json(['data' => $transactions], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve transactions.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Validation (similar to store method, adjust as needed)
        $validator = Validator::make($request->all(), [
            'transaction_category_id' => 'exists:transaction_categories,id',
            'type' => 'in:income,expense',
            'amount' => 'integer|min:0',
            'description' => 'nullable|string',
            'proof' => 'nullable|string',
            'date' => 'date',
            'verification_status' => 'in:pending,awaiting_verification,completed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $transaction->fill($request->all()); 
            $transaction->save();
            DB::commit();
            return response()->json(['message' => 'Transaction updated successfully', 'data' => $transaction], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction update failed', 'error' => $e->getMessage()], 500);
        }

    }


    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();
        try {
            $transaction->delete();
            DB::commit();
            return response()->json(['message' => 'Transaction deleted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction deletion failed', 'error' => $e->getMessage()], 500);
        }
    }


    public function index()
    {
        // $transactions = Transaction::all();
        $transactions = Transaction::paginate(10);
        return response()->json(['data' => $transactions], 200);
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        return response()->json(['data' => $transaction], 200);
    }
}