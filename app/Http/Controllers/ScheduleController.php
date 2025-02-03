<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ScheduleController extends Controller
{
    public function index()
    {
        try {
            $schedules = Schedule::all();
            return response()->json($schedules, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve schedules'], 500);
        }
    }

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
            return response()->json($schedule, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create schedule'], 500);
        }
    }

    public function show(Schedule $schedule)
    {
        try {
            return response()->json($schedule, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve schedule'], 500);
        }
    }

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
            return response()->json($schedule, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update schedule'], 500);
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();
            return response()->noContent();
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete schedule'], 500);
        }
    }
}
