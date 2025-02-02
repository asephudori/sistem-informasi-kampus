<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Display a listing of the faculties.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Faculty::all();
    }

    /**
     * Store a newly created faculty in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:faculties'
        ]);

        $faculty = Faculty::create($validated);
        return response()->json($faculty, 201);
    }

    /**
     * Display the specified faculty.
     *
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function show(Faculty $faculty)
    {
        return $faculty;
    }

    /**
     * Update the specified faculty in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:faculties,name,' . $faculty->id
        ]);

        $faculty->update($validated);
        return response()->json($faculty);
    }

    /**
     * Remove the specified faculty from storage.
     *
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return response()->json(null, 204);
    }
}