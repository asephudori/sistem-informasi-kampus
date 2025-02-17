<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterFee extends Model
{
    use HasFactory;

    protected $table = 'semester_fee'; 
    protected $fillable = ['semester_id', 'student_id', 'transaction_id', 'payment_status', 'payment_proof', 'due_date'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }
}
