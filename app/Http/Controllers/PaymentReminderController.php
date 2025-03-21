<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SemesterFee;
use App\Models\Admin;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PaymentReminderController extends Controller
{
    use Loggable;
    public function sendReminder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admin_ids' => 'required|array',
                'admin_ids.*' => 'exists:admins,user_id',
                'custom_message' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $adminIds = $request->input('admin_ids');
            $customMessage = $request->input('custom_message');

            $admins = Admin::whereIn('user_id', $adminIds)->get();

            $students = Student::whereHas('semesterFees', function ($query) {
                $query->where('payment_status', 'unpaid');
            })->with(['semesterFees' => function ($query) {
                $query->where('payment_status', 'unpaid');
            }])->get();

            $sentReminders = [];

            foreach ($students as $student) {
                foreach ($student->semesterFees as $semesterFee) {
                    $dueDate = $semesterFee->due_date;

                    $isNearDueDate = Carbon::today()->addDays(30)->isSameDay(Carbon::parse($dueDate)) || (Carbon::today()->isMonday() && Carbon::today()->addDays(7)->isSameDay(Carbon::parse($dueDate)));

                    if ($isNearDueDate) {
                        foreach ($admins as $admin) {
                            $this->sendEmail($student, $admin->email, $customMessage, $dueDate, false);

                            $sentReminders[] = [
                                'student_id' => $student->user_id,
                                'student_name' => $student->name,
                                'email' => $student->email,
                                'due_date' => $semesterFee->due_date,
                            ];
                        }
                    }
                }
            }

            $this->logActivity(
                'New Send Reminder Created!',
                'Activity Detail: ' . $sentReminders,
                "Create"
            );
            return response()->json([
                'message' => 'Reminders sent successfully (due date based)',
                'sent_reminders' => $sentReminders,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error sending reminders: ' . $e->getMessage());
            return response()->json(['message' => 'Error sending reminders: ' . $e->getMessage()], 500);
        }
    }

    public function sendReminderManual(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admin_ids' => 'required|array',
                'admin_ids.*' => 'exists:admins,user_id',
                'custom_message' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $adminIds = $request->input('admin_ids');
            $customMessage = $request->input('custom_message');

            $admins = Admin::whereIn('user_id', $adminIds)->get();

            $students = Student::whereHas('semesterFees', function ($query) {
                $query->where('payment_status', 'unpaid');
            })->with(['semesterFees' => function ($query) {
                $query->where('payment_status', 'unpaid');
            }])->get();

            $sentReminders = [];

            foreach ($students as $student) {
                foreach ($student->semesterFees as $semesterFee) {
                    $dueDate = $semesterFee->due_date;

                    foreach ($admins as $admin) {
                        $this->sendEmail($student, $admin->email, $customMessage, $dueDate, false);

                        $sentReminders[] = [
                            'student_id' => $student->user_id,
                            'student_name' => $student->name,
                            'email' => $student->email,
                            'due_date' => $semesterFee->due_date,
                        ];
                    }
                }
            }

            $this->logActivity(
                'New Send Reminder Created!',
                'Activity Detail: ' . $sentReminders,
                "Create"
            );
            return response()->json([
                'message' => 'Reminders sent manually',
                'sent_reminders' => $sentReminders,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error sending manual reminders: ' . $e->getMessage());
            return response()->json(['message' => 'Error sending manual reminders: ' . $e->getMessage()], 500);
        }
    }

    public function uploadPaymentProof(Request $request, $semesterFeeId)
    {
        try {
            $semesterFee = SemesterFee::find($semesterFeeId);

            if (!$semesterFee) {
                return response()->json(['message' => 'Semester fee not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $semesterFee->payment_proof = $path;
            $semesterFee->payment_status = 'awaiting_verification';
            $semesterFee->save();

            $this->logActivity(
                'New Send Reminder Created!',
                'Activity Detail: ' . $semesterFee,
                "Create"
            );
            return response()->json(['message' => 'Payment proof uploaded successfully', 'payment_proof_path' => $path], 200);
        } catch (\Exception $e) {
            Log::error('Error uploading payment proof: ' . $e->getMessage());
            return response()->json(['message' => 'Error uploading payment proof: ' . $e->getMessage()], 500);
        }
    }

    public function sendSuccessNotification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admin_ids' => 'required|array',
                'admin_ids.*' => 'exists:admins,user_id',
                'custom_message' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $adminIds = $request->input('admin_ids');
            $customMessage = $request->input('custom_message');

            $admins = Admin::whereIn('user_id', $adminIds)->get();

            $semesterFees = SemesterFee::where('payment_status', 'awaiting_verification')->with('student')->get();

            if ($semesterFees->isEmpty()) {
                return response()->json(['message' => 'No semester fees found with awaiting_verification status'], 404);
            }

            $sentNotifications = [];
            $notifiedStudents = [];
            $failedNotifications = [];

            foreach ($semesterFees as $semesterFee) {
                $student = $semesterFee->student;

                if ($student) {
                    foreach ($admins as $admin) {
                        $emailSent = $this->sendEmail($student, $admin->email, $customMessage, null, true); // Perhatikan nilai true di sini

                        if ($emailSent) {
                            $semesterFee->payment_status = 'paid';
                            $semesterFee->save();

                            $sentNotifications[] = [
                                'student_id' => $student->user_id,
                                'student_name' => $student->name,
                                'email' => $student->email,
                                'semester_fee_id' => $semesterFee->id,
                            ];

                            if (!in_array($student->user_id, array_column($notifiedStudents, 'student_id'))) {
                                $notifiedStudents[] = [
                                    'student_id' => $student->user_id,
                                    'student_name' => $student->name,
                                    'email' => $student->email,
                                ];
                            }
                        } else {
                            $failedNotifications[] = [
                                'student_id' => $student->user_id,
                                'student_name' => $student->name,
                                'email' => $student->email,
                                'semester_fee_id' => $semesterFee->id,
                                'error_message' => 'Failed to send email',
                            ];
                        }
                    }
                }
            }

            $response = [
                'message' => 'Success notifications processed',
                'sent_notifications' => $sentNotifications,
                'notified_students' => $notifiedStudents,
            ];

            if (!empty($failedNotifications)) {
                $response['failed_notifications'] = $failedNotifications;
                $response['message'] .= ' with some failures';
                return response()->json($response, 500);
            }

            $this->logActivity(
                'New Send Success Notification Created!',
                'Activity Detail: ' . $sentNotifications,
                "Create"
            );

            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error('Error processing success notifications: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing success notifications: ' . $e->getMessage()], 500);
        }
    }

    private function sendEmail($student, $adminEmail, $customMessage, $dueDate = null, $isSuccess = false)
    {
        $subject = $isSuccess ? 'Payment Successful' : 'Payment Reminder';
        $signature = view('emails.signature')->render();

        Mail::send('emails.reminder', [
            'student' => $student,
            'customMessage' => $customMessage,
            'dueDate' => $dueDate ? Carbon::parse($dueDate) : null,
            'isSuccess' => $isSuccess,
            'signature' => $signature,
        ], function ($mail) use ($student, $adminEmail, $subject, $isSuccess, $customMessage, $dueDate, $signature) {
            $mail->from(config('mail.from.address'), config('mail.from.name'));
            $mail->to($student->email);
            $mail->subject($subject);
            $mail->replyTo($adminEmail);

            // Plain text version
            $mail->text(view('emails.reminder_plain', [
                'student' => $student,
                'customMessage' => $customMessage,
                'dueDate' => $dueDate ? Carbon::parse($dueDate) : null,
                'isSuccess' => $isSuccess,
                'signature' => $signature,
            ])->render());
        });

        return true;
    }

    // set due date
    public function setDueDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'due_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $dueDate = $request->input('due_date');

            // Update semua data siswa dalam satu query
            SemesterFee::query()->update(['due_date' => $dueDate]);

            $this->logActivity(
                'New Due Date Created!',
                'Activity Detail: ' . $dueDate,
                "Create"
            );
            return response()->json(['message' => 'Due dates set for all students successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to set due dates. ' . $e->getMessage()], 500);
        }
    }

    // update due date
    public function editDueDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'due_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $newDueDate = $request->input('due_date');

            // Update semua data siswa dalam satu query
            SemesterFee::query()->update(['due_date' => $newDueDate]);

            $this->logActivity(
                'New Due Date Updated!',
                'Activity Detail: ' . $newDueDate,
                "Update"
            );
            return response()->json(['message' => 'Due dates updated for all students successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update due dates. ' . $e->getMessage()], 500);
        }
    }

    public function getPaymentProofs(Request $request)
    {
        $semesterFees = SemesterFee::whereNotNull('payment_proof')->with('student')->get();

        $paymentProofs = $semesterFees->map(function ($semesterFee) {
            return [
                'id' => $semesterFee->id,
                'student_name' => $semesterFee->student ? $semesterFee->student->name : 'N/A',
                'payment_proof_url' => $semesterFee->payment_proof ? Storage::disk('public')->url($semesterFee->payment_proof) : null, // Check if path exists
                'payment_status' => $semesterFee->payment_status,
            ];
        });

        return response()->json($paymentProofs);
    }

    public function getAdmins()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }
}
