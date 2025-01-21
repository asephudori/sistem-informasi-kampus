<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityInformations extends Model
{
    use HasFactory;
    protected $table = 'university_informations';
    protected $fillable = ['name', 'address', 'regency', 'postal_code', 'logo'];
    public $timestamps = false; // Karena tabel tidak memiliki kolom created_at dan updated_at
}
