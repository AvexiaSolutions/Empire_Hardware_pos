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
        Schema::table('credits', function (Blueprint $table) {
            $table->string('type')->default('received')->after('id'); // received (from customer), issued (to supplier)
            $table->unsignedBigInteger('supplier_id')->nullable()->after('invoice_id');
            $table->unsignedBigInteger('invoice_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn(['type', 'supplier_id']);
            $table->unsignedBigInteger('invoice_id')->nullable(false)->change();
        });
    }
};
