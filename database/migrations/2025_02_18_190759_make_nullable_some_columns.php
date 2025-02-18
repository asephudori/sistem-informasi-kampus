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
        Schema::table('students', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->string('birthplace')->nullable()->change();
            $table->date('birthdate')->nullable()->change();
            $table->string('home_address')->nullable()->change();
            $table->string('current_address')->nullable()->change();
            $table->string('home_city_district')->nullable()->change();
            $table->string('home_postal_code')->nullable()->change();
        });

        Schema::table('lecturers', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->string('birthplace')->nullable()->change();
            $table->date('birthdate')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->date('entry_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
            $table->string('birthplace')->nullable(false)->change();
            $table->date('birthdate')->nullable(false)->change();
            $table->string('home_address')->nullable(false)->change();
            $table->string('current_address')->nullable(false)->change();
            $table->string('home_city_district')->nullable(false)->change();
            $table->string('home_postal_code')->nullable(false)->change();
        });

        Schema::table('lecturers', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
            $table->string('birthplace')->nullable(false)->change();
            $table->date('birthdate')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->date('entry_date')->nullable(false)->change();
        });
    }
};
