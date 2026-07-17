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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('opening_time');
            $table->dateTime('closing_time')->nullable();
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->decimal('closing_balance', 10, 2)->nullable();
            $table->decimal('expected_closing_balance', 10, 2)->nullable();
            $table->string('status')->default('Open'); // Open, Closed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add cash_register_id to invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('cash_register_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('cash_register_id');
        });
        Schema::dropIfExists('cash_registers');
    }
};
