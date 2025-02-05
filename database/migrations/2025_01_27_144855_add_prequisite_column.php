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
        Schema::table('courses', function (Blueprint $table) {
            // Menambahkan kolom prerequisite_id
            $table->unsignedBigInteger('prerequisite_id')->nullable();

            // Menambahkan foreign key yang mengarah ke kolom id di tabel courses
            $table->foreign('prerequisite_id')
                  ->references('id')->on('courses')
                  ->onDelete('set null'); // Atau bisa pilih 'cascade' jika ingin menghapus course yang terkait
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Hapus foreign key dan kolom prerequisite_id
            $table->dropForeign(['prerequisite_id']);
            $table->dropColumn('prerequisite_id');
        });
    }
};
