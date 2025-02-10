<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use Throwable;

class NewsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth'); // Memastikan pengguna sudah login
    //     $this->middleware('newsRole')->except(['index']); // Memeriksa role untuk akses selain index
    // }

    public function index(Request $request)
    {
        try {
            $news = News::with('admin')->paginate(10);

            return response()->json($news);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error retrieving news', 'error' => $e->getMessage()], 500);
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
            return response()->json(['message' => 'News deleted'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'News not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error deleting news', 'error' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'keyword' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'per_page' => 'nullable|integer|min:1',
                'page' => 'nullable|integer|min:1',
            ]);

            $keyword = $request->query('keyword');
            $startDate = $request->query('start_date');
            $endDate = $request->query('end_date');
            $perPage = $request->query('per_page', 10);
            $page = $request->query('page', 1);

            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            $query = News::with('admin');

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%$keyword%")
                        ->orWhere('description', 'like', "%$keyword%");
                });
            }

            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            $news = $query->paginate($perPage);

            if ($news->isEmpty()) {
                return response()->json(['data' => [], 'message' => 'No news found'], 200);
            }

            return response()->json($news);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error searching news', 'error' => $e->getMessage()], 500);
        }
    }
}
