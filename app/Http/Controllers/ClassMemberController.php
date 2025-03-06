<?php

namespace App\Http\Controllers;

use App\Models\ClassMember;
use App\Models\User;
use App\Models\LearningClass;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable; // Import Throwable for catching any exception

class ClassMemberController extends Controller
{
    use Loggable;
    // show all class member with search func
    public function index(Request $request)
    {
        try {
            $query = ClassMember::with(['class', 'user']);

            // Search functionality (combining all search criteria)
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->whereHas('class', function ($classQuery) use ($search) { // Search in class name
                        $classQuery->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) { // Search in user name
                        $userQuery->where('name', 'like', "%$search%");
                    })
                    ->orWhere('semester_grades', 'like', "%$search%"); // Search in semester grades
                });
            }


            $classMembers = $query->paginate(10); // Paginate the results

            return response()->json(['status' => 'success', 'data' => $classMembers]);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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
            $this->logActivity('New Class Member Created!', 'Class Member Detail: ' . $classMember, "Create");
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
            $this->logActivity('New Class Member Updated!', 'Class Member Detail: ' . $classMember, "Update");
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
            $this->logActivity('New Class Member Deleted!', 'Class Member Detail: ' . $classMember, "Delete");
            return response()->json(['message' => 'Class member deleted'], 200);
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
}
