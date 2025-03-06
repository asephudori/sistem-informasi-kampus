<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\LearningClass;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ScheduleController extends Controller
{
    use Loggable;
    // show all schedules
    public function index()
    {
        try {
            $schedules = Schedule::all();
            return response()->json($schedules, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve schedules: ' . $e->getMessage()], 500);
        }
    }

    // create schedule
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $schedule = Schedule::create($validator->validated());
            $this->logActivity('New Schedule Created!', 'Schedule Detail: ' . $schedule, "Create");
            return response()->json($schedule, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create schedule: ' . $e->getMessage()], 500);
        }
    }

    // show schedule by id
    public function show(Schedule $schedule)
    {
        try {
            return response()->json($schedule, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve schedule: ' . $e->getMessage()], 500);
        }
    }

    // update schedules
    public function update(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $schedule->update($validator->validated());
            $this->logActivity('New Schedule Updated!', 'Schedule Detail: ' . $schedule, "Update");
            return response()->json($schedule, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update schedule: ' . $e->getMessage()], 500);
        }
    }

    // delete schedules
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();
            $this->logActivity('New Schedule Deleted!', 'Schedule Detail: ' . $schedule, "Delete");
            return response()->json(['message' => 'Schedule deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete schedule: ' . $e->getMessage()], 500);
        }
    }

    // show schedule with class info
    public function showWithClassInfo(Schedule $schedule)
    {
        try {
            $schedule->load('class'); // Load the 'class' (LearningClass) relationship
            return response()->json($schedule, 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve schedule or class information: ' . $e->getMessage()], 500);
        }
    }

    // show schedule by class id
    public function getSchedulesByClassId($classId)
    {
        try {
            $schedules = Schedule::where('class_id', $classId)->with('class')->get();

            if ($schedules->isEmpty()) {
                return response()->json(['message' => 'No schedules found for this class ID'], 404);
            }

            return response()->json($schedules, 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve schedules: ' . $e->getMessage()], 500);
        }
    }
}