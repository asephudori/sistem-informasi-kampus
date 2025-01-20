<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('university_informations', function (Blueprint $table) {
            $table->string('name');
            $table->string('address');
            $table->string('regency');
            $table->string('postal_code');
            $table->string('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('university_informations', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('address');
            $table->dropColumn('regency');
            $table->dropColumn('postal_code');
            $table->dropColumn('logo');
        });
    }
};
