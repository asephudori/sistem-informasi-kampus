<?php

namespace App\Http\Controllers;

use App\Models\ClassMember;
use App\Models\User;
use App\Models\LearningClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable; // Import Throwable for catching any exception

class ClassMemberController extends Controller
{
    // show all class member
    public function index()
    {
        try {
            $classMembers = ClassMember::with(['class', 'user'])->get();
            return response()->json($classMembers);
        } catch (Throwable $e) { // Catch any exception
            return response()->json(['message' => 'Error retrieving class members', 'error' => $e->getMessage()], 500);
        }
    }

    // create class member
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:classes,id',
                'student_id' => 'required|exists:users,id',
                'semester_grades' => 'required|string|max:5',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $classMember = ClassMember::create($request->all());
            return response()->json($classMember, 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error creating class member', 'error' => $e->getMessage()], 500);
        }
    }

    // show class member by id
    public function show($id)
    {
        try {
            $classMember = ClassMember::with(['class', 'user'])->findOrFail($id); // Use findOrFail
            return response()->json($classMember);
        } catch (ModelNotFoundException $e) { // Catch ModelNotFoundException
            return response()->json(['message' => 'Class member not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error retrieving class member', 'error' => $e->getMessage()], 500);
        }
    }

    // update class member
    public function update(Request $request, $id)
    {
        try {
            $classMember = ClassMember::findOrFail($id); // Use findOrFail

            $validator = Validator::make($request->all(), [
                'class_id' => 'exists:classes,id',
                'student_id' => 'exists:users,id',
                'semester_grades' => 'string|max:5',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $classMember->update($request->all());
            return response()->json($classMember, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Class member not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error updating class member', 'error' => $e->getMessage()], 500);
        }
    }

    // delete class member
    public function destroy($id)
    {
        try {
            $classMember = ClassMember::findOrFail($id); // Use findOrFail
            $classMember->delete();
            return response()->json(['message' => 'Class member deleted'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Class member not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error deleting class member', 'error' => $e->getMessage()], 500);
        }
    }

    // show class member by class id
    public function getClassMembersByClass($classId)
    {
        try {
            $classMembers = ClassMember::where('class_id', $classId)->with('user')->get();
            return response()->json($classMembers);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error retrieving class members', 'error' => $e->getMessage()], 500);
        }
    }

    // Search class members based on class_id, student_id, or semester_grades
    public function search(Request $request)
    {
        try {
            // Get the search parameters from the request
            $classId = $request->query('class_id');
            $studentId = $request->query('student_id');
            $semesterGrades = $request->query('semester_grades');

            // Start building the query
            $query = ClassMember::with(['class', 'user']);

            // Apply filters based on provided parameters
            if ($classId) {
                $query->where('class_id', $classId);
            }

            if ($studentId) {
                $query->where('student_id', $studentId);
            }

            if ($semesterGrades) {
                $query->where('semester_grades', 'like', '%' . $semesterGrades . '%');
            }

            // Execute the query and get the results
            $classMembers = $query->get();

            return response()->json($classMembers);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error searching class members', 'error' => $e->getMessage()], 500);
        }
    }
}
