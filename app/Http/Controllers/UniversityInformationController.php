<?php

namespace App\Http\Controllers;

use App\Models\UniversityInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class UniversityInformationController extends Controller
{
    public function index()
    {
        try {
            $universityInformations = UniversityInformation::all(); // Ambil semua data tanpa pagination
            return response()->json($universityInformations);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error retrieving university information', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'regency' => 'required|string|max:255',
                'postal_code' => 'required|string|max:5',
                'logo' => 'required|string', // Or handle file uploads
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $universityInformation = UniversityInformation::create($request->all());
            return response()->json($universityInformation, 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error creating university information', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $universityInformation = UniversityInformation::findOrFail($id);
            return response()->json($universityInformation);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'University information not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error retrieving university information', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $universityInformation = UniversityInformation::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'address' => 'string',
                'regency' => 'string|max:255',
                'postal_code' => 'string|max:5',
                'logo' => 'string', // Or handle file uploads
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $universityInformation->update($request->all());
            return response()->json($universityInformation, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'University information not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error updating university information', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $universityInformation = UniversityInformation::findOrFail($id);
            $universityInformation->delete();
            return response()->json(['message' => 'University information deleted'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'University information not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error deleting university information', 'error' => $e->getMessage()], 500);
        }
    }
}