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
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('has_expiry_date')->default(false)->after('base_unit');
        });

        Schema::table('item_batches', function (Blueprint $table) {
            $table->date('expiry_date')->nullable()->after('selling_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('has_expiry_date');
        });

        Schema::table('item_batches', function (Blueprint $table) {
            $table->dropColumn('expiry_date');
        });
    }
};
