<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use Throwable;

class NewsController extends Controller
{
    use Loggable;
    public function index(Request $request)
    {
        try {
            $query = News::with('admin');

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');

                // Validate date format (important!)
                $isValidStartDate = \DateTime::createFromFormat('Y-m-d', $startDate) !== false;
                $isValidEndDate = \DateTime::createFromFormat('Y-m-d', $endDate) !== false;

                if (!$isValidStartDate || !$isValidEndDate) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid date format. Use YYYY-MM-DD.'], 400); // Bad Request
                }


                $query->whereBetween('date', [$startDate, $endDate]);
            } elseif ($request->has('start_date') && !$request->has('end_date')) {
                $startDate = $request->input('start_date');
                $isValidStartDate = \DateTime::createFromFormat('Y-m-d', $startDate) !== false;
                if (!$isValidStartDate) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid start date format. Use YYYY-MM-DD.'], 400); // Bad Request
                }
                $query->where('date', '>=', $startDate);
            } elseif (!$request->has('start_date') && $request->has('end_date')) {
                $endDate = $request->input('end_date');
                $isValidEndDate = \DateTime::createFromFormat('Y-m-d', $endDate) !== false;
                if (!$isValidEndDate) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid end date format. Use YYYY-MM-DD.'], 400); // Bad Request
                }
                $query->where('date', '<=', $endDate);
            }

            $news = $query->orderBy('date', 'desc')->paginate(10);

            return response()->json(['status' => 'success', 'data' => $news]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admin_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|string',
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $news = News::create($request->all());
            $this->logActivity(
                'New News Created!',
                'Activity Detail: ' . $news,
                "Create"
            );
            return response()->json($news, 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error creating news', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $news = News::with('admin')->findOrFail($id);
            return response()->json($news);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'News not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error retrieving news', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $news = News::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'admin_id' => 'exists:users,id',
                'title' => 'string|max:255',
                'description' => 'string',
                'image' => 'nullable|string',
                'date' => 'date',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $news->update($request->all());
            $this->logActivity(
                'New News Updated!',
                'Activity Detail: ' . $news,
                "Update"
            );
            return response()->json($news, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'News not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error updating news', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);
            $news->delete();
            $this->logActivity(
                'New News Deleted!',
                'Activity Detail: ' . $news,
                "Delete"
            );
            return response()->json(['message' => 'News deleted'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'News not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error deleting news', 'error' => $e->getMessage()], 500);
        }
    }
}
