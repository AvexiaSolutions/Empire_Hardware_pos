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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('tendered_amount', 10, 2)->default(0)->after('total');
            $table->decimal('balance_amount', 10, 2)->default(0)->after('tendered_amount');
            $table->decimal('bill_discount', 10, 2)->default(0)->after('sub_total'); // The overall bill discount
            $table->string('bill_discount_type')->default('amount')->after('bill_discount'); // 'amount' or 'percentage'
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('unit_price');
            $table->string('discount_type')->default('amount')->after('discount'); // 'amount' or 'percentage'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['tendered_amount', 'balance_amount', 'bill_discount', 'bill_discount_type']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['discount', 'discount_type']);
        });
    }
};
