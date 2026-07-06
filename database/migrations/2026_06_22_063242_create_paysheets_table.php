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
        Schema::create('paysheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->string('month_year');
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('performance_allowance', 10, 2)->default(0);
            $table->decimal('welfare_deduction', 10, 2)->default(0);
            $table->decimal('no_pay_deduction', 10, 2)->default(0);
            $table->decimal('tax_deduction', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paysheets');
    }
};
