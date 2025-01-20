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
        Schema::table('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_category_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['income', 'expense']);
            $table->integer('amount');
            $table->text('description');
            $table->string('proof');
            $table->date('date');
            $table->enum('verification_status', ['pending', 'awaiting_verification', 'completed']);
            $table->timestamp();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
