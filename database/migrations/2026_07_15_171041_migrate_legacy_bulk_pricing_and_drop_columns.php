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
        // 1. Migrate legacy data
        $items = DB::table('items')->where('has_bulk_unit', true)->get();
        foreach ($items as $item) {
            $bulkUnitId = DB::table('item_bulk_units')->insertGetId([
                'item_id' => $item->id,
                'name' => $item->bulk_unit ?: 'Bulk',
                'conversion_factor' => $item->bulk_conversion_factor ?: 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $batches = DB::table('item_batches')->where('item_id', $item->id)->get();
            foreach ($batches as $batch) {
                if ($batch->bulk_selling_price > 0 || $batch->bulk_cost_price > 0) {
                    DB::table('item_batch_bulk_prices')->insert([
                        'item_batch_id' => $batch->id,
                        'item_bulk_unit_id' => $bulkUnitId,
                        'cost_price' => $batch->bulk_cost_price,
                        'selling_price' => $batch->bulk_selling_price,
                        'discount' => $batch->bulk_discount,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        // 2. Drop columns
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['has_bulk_unit', 'bulk_unit', 'bulk_conversion_factor']);
        });

        Schema::table('item_batches', function (Blueprint $table) {
            $table->dropColumn(['bulk_cost_price', 'bulk_selling_price', 'bulk_discount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('has_bulk_unit')->default(false);
            $table->string('bulk_unit')->nullable();
            $table->decimal('bulk_conversion_factor', 10, 2)->nullable();
        });

        Schema::table('item_batches', function (Blueprint $table) {
            $table->decimal('bulk_cost_price', 10, 2)->default(0);
            $table->decimal('bulk_selling_price', 10, 2)->default(0);
            $table->string('bulk_discount')->nullable();
        });
    }
};
