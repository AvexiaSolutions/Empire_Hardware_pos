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
            $table->renameColumn('primary_unit', 'base_unit');
            $table->dropColumn('secondary_unit');
            $table->renameColumn('conversion_factor', 'bulk_conversion_factor');
            $table->boolean('has_bulk_unit')->default(false)->after('image');
            $table->string('bulk_unit')->nullable()->after('has_bulk_unit');
        });

        Schema::table('item_batches', function (Blueprint $table) {
            $table->decimal('bulk_cost_price', 10, 2)->default(0)->after('selling_price');
            $table->decimal('bulk_selling_price', 10, 2)->default(0)->after('bulk_cost_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('base_unit', 'primary_unit');
            $table->string('secondary_unit')->nullable();
            $table->renameColumn('bulk_conversion_factor', 'conversion_factor');
            $table->dropColumn(['has_bulk_unit', 'bulk_unit']);
        });

        Schema::table('item_batches', function (Blueprint $table) {
            $table->dropColumn(['bulk_cost_price', 'bulk_selling_price']);
        });
    }
};
