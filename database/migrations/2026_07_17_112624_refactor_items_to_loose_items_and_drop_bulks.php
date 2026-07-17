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
        // Add new columns to items table
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('is_loose')->default(false)->after('name');
            $table->foreignId('parent_item_id')->nullable()->constrained('items')->nullOnDelete()->after('is_loose');
        });

        // Drop the invoice_items bulk columns (using safe checks)
        Schema::table('invoice_items', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_items', 'item_bulk_unit_id')) {
                $table->dropColumn('item_bulk_unit_id');
            }
            if (Schema::hasColumn('invoice_items', 'conversion_factor')) {
                $table->dropColumn('conversion_factor');
            }
        });

        // Drop old bulk tables entirely
        Schema::dropIfExists('item_batch_bulk_prices');
        Schema::dropIfExists('item_bulk_units');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['parent_item_id']);
            $table->dropColumn(['is_loose', 'parent_item_id']);
        });

        // We don't restore the old bulk tables in down() as they are obsolete 
        // and recreating them precisely would require copying their original schemas here.
    }
};
