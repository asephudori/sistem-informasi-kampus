<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    use HasFactory;
    protected $table = 'transaction_categories';
    protected $fillable = ['name', 'description', 'type'];

    public function transactions() 
    {
        return $this->hasMany(Transaction::class); 
    }
}
