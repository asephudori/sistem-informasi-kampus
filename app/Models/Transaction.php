<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    
    protected $table = 'transactions';
    
    protected $fillable = ['transaction_category_id', 'admin_id', 'type', 'amount', 'description', 'proof', 'date', 'verification_status'];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionCategory()
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    public function semesterFee()
    {
        return $this->hasOne(SemesterFee::class);
    }
}

