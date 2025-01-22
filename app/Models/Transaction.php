<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['transaction_category_id', 'admin_id', 'type', 'amount', 'description', 'proof', 'date', 'verification_status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionCategory()
    {
        return $this->belongsTo(TransactionCategory::class);
    }
}

