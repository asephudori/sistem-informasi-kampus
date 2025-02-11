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
            $query = News::with('admin');
    
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
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
}
