<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UniversityInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UniversityInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UniversityInformation::create([
            'name' => 'Techno Net',
            'address' => 'Jl. Ciledug Raya No. 99, Petukangan Utara, Pesanggrahan, Jakarta Selatan',
            'regency' => 'Jakarta Selatan',
            'postal_code' => '12260',
            'logo' => '',
        ]);
    }
}
