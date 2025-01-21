<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterFee extends Model
{
    use HasFactory;

    protected $table = 'semester_fee'; // Explicitly define the table name
    protected $fillable = ['semester_id', 'user_id', 'transaction_id', 'payment_status', 'payment_proof'];

    public function semester()
    {
        return $this->belongsTo(Semesters::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transactions::class); // Asumsi ada model Transaction
    }
}
