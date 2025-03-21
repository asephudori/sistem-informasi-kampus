<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use Loggable;
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

            $this->logActivity(
                'New Transaction Created!',
                'Activity Detail: ' . $transaction,
                "Create"
            );
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

    // get all transaction by category id
    public function transactionsByCategoryReport(Request $request)
    {
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

    // update transaction
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
            $this->logActivity(
                'New Transaction Updated!',
                'Activity Detail: ' . $transaction,
                "Update"
            );
            return response()->json(['message' => 'Transaction updated successfully', 'data' => $transaction], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction update failed', 'error' => $e->getMessage()], 500);
        }
    }

    // delete transaction
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
            $this->logActivity(
                'New Transaction Deleted!',
                'Activity Detail: ' . $transaction,
                "Delete"
            );
            return response()->json(['message' => 'Transaction deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    // get all transaction include pagination
    public function index()
    {
        // $transactions = Transaction::all();
        $transactions = Transaction::paginate(10);
        return response()->json(['data' => $transactions], 200);
    }

    // get transaction by id
    public function show($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        return response()->json(['data' => $transaction], 200);
    }

    // laporan keuangan
    public function financialReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'transaction_category_id' => 'nullable|exists:transaction_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $categoryId = $request->input('transaction_category_id');

            $report = [];
            $transactionsFound = false;

            if ($categoryId) {
                // Detailed report for a specific category
                $category = TransactionCategory::find($categoryId);
                $transactions = Transaction::where('transaction_category_id', $categoryId)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();

                if ($transactions->isNotEmpty()) { // Periksa apakah ada transaksi
                    $transactionsFound = true;
                    $totalIncome = $transactions->where('type', 'income')->sum('amount');
                    $totalExpense = $transactions->where('type', 'expense')->sum('amount');

                    $report[] = [
                        'category_id' => $category->id,
                        'category_name' => $category->name,
                        'category_type' => $category->type,
                        'total_income' => $this->formatCurrency($totalIncome),
                        'total_expense' => $this->formatCurrency($totalExpense),
                        'net_amount' => $this->formatCurrency($totalIncome - $totalExpense),
                        'transactions' => $transactions,
                    ];
                }
            } else {
                // Overall financial report for all categories
                $categories = TransactionCategory::all();

                foreach ($categories as $category) {
                    $transactions = Transaction::where('transaction_category_id', $category->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->get();

                    if ($transactions->isNotEmpty()) { // Periksa apakah ada transaksi
                        $transactionsFound = true;
                        $totalIncome = $transactions->where('type', 'income')->sum('amount');
                        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

                        $report[] = [
                            'category_id' => $category->id,
                            'category_name' => $category->name,
                            'category_type' => $category->type,
                            'total_income' => $this->formatCurrency($totalIncome),
                            'total_expense' => $this->formatCurrency($totalExpense),
                            'net_amount' => $this->formatCurrency($totalIncome - $totalExpense),
                        ];
                    }
                }
            }

            if (!$transactionsFound) {
                return response()->json([
                    'message' => 'No transactions found for the specified date range.',
                    'report' => [],
                    'overall_total_income' => '0,00',
                    'overall_total_expense' => '0,00',
                    'overall_net_amount' => '0,00',
                ], 200);
            }

            // Calculate overall totals
            $overallTotalIncome = collect($report)->sum(function ($item) {
                return $this->unFormatCurrency($item['total_income']);
            });
            $overallTotalExpense = collect($report)->sum(function ($item) {
                return $this->unFormatCurrency($item['total_expense']);
            });
            $overallNetAmount = $overallTotalIncome - $overallTotalExpense;

            return response()->json([
                'report' => $report,
                'overall_total_income' => $this->formatCurrency($overallTotalIncome),
                'overall_total_expense' => $this->formatCurrency($overallTotalExpense),
                'overall_net_amount' => $this->formatCurrency($overallNetAmount),
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to generate financial report.', 'error' => $e->getMessage()], 500);
        }
    }

    // Helper function to format currency
    private function formatCurrency($amount)
    {
        return number_format($amount, 2, ',', '.');
    }

    // Helper function to unformat currency for calculation
    private function unFormatCurrency($amount)
    {
        return (float) str_replace(['.', ','], ['', '.'], $amount);
    }
}
