<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\ItemBulkUnit;
use App\Models\ItemBatchBulkPrice;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TestItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();

        if ($categories->isEmpty()) {
            $category = Category::create(['name' => 'General']);
            $categories->push($category);
        }

        $baseUnits = ['pcs', 'kg', 'ltr', 'box'];
        $bulkUnitsNames = ['box', 'carton', 'pack', 'dozen'];

        for ($i = 1; $i <= 100; $i++) {
            $hasExpiry = rand(0, 1);
            $hasWarranty = rand(0, 1);
            $hasBulk = rand(0, 1);

            $cat = $categories->random();
            $subCatsForCat = $subCategories->where('category_id', $cat->id);
            
            if ($subCatsForCat->isEmpty()) {
                $subCat = SubCategory::create(['name' => 'Gen Sub ' . $cat->id, 'category_id' => $cat->id]);
                $subCategories->push($subCat);
            } else {
                $subCat = $subCatsForCat->random();
            }

            $item = Item::create([
                'code' => 'ITM-TEST-' . str_pad($i, 4, '0', STR_PAD_LEFT) . '-' . Str::random(4),
                'name' => 'Test Item ' . $i . ($hasExpiry ? ' (Exp)' : '') . ($hasWarranty ? ' (Wrnty)' : '') . ($hasBulk ? ' (Bulk)' : ''),
                'base_unit' => $baseUnits[array_rand($baseUnits)],
                'has_expiry_date' => $hasExpiry,
                'has_warranty' => $hasWarranty,
                'warranty_months' => $hasWarranty ? rand(1, 24) : 0,
                'category_id' => $cat->id,
                'sub_category_id' => $subCat->id,
                'search_aliases' => 'test,item'.$i,
                'rack_number' => 'R' . rand(1, 10),
                'rack_row' => 'Row ' . rand(1, 5),
            ]);

            // Create Bulk Units
            $bulkUnits = [];
            if ($hasBulk) {
                $numBulk = rand(1, 3);
                for ($j = 1; $j <= $numBulk; $j++) {
                    $bulkUnits[] = ItemBulkUnit::create([
                        'item_id' => $item->id,
                        'name' => $bulkUnitsNames[array_rand($bulkUnitsNames)] . ' of ' . (10 * $j),
                        'conversion_factor' => 10 * $j,
                    ]);
                }
            }

            // Create Batches
            $numBatches = rand(1, 3);
            for ($k = 1; $k <= $numBatches; $k++) {
                $cost = rand(10, 1000);
                $selling = $cost + rand(10, 500);

                $batch = ItemBatch::create([
                    'item_id' => $item->id,
                    'batch_no' => 'B-' . $item->id . '-' . $k,
                    'barcode' => 'BAR-' . $item->id . '-' . $k,
                    'cost_price' => $cost,
                    'selling_price' => $selling,
                    'expiry_date' => $hasExpiry ? Carbon::now()->addDays(rand(30, 365)) : null,
                    'discount' => rand(0, 1) ? rand(5, 20) : 0,
                    'quantity' => rand(50, 500),
                    'damaged_quantity' => 0,
                    'is_active' => true,
                    'is_printed' => false,
                ]);

                // Create Bulk Prices for each batch if has bulk
                if ($hasBulk) {
                    foreach ($bulkUnits as $bulkUnit) {
                        $bulkCost = $cost * $bulkUnit->conversion_factor * 0.9; // 10% discount on cost for bulk
                        $bulkSelling = $selling * $bulkUnit->conversion_factor * 0.95; // 5% discount on selling price for bulk

                        ItemBatchBulkPrice::create([
                            'item_batch_id' => $batch->id,
                            'item_bulk_unit_id' => $bulkUnit->id,
                            'cost_price' => $bulkCost,
                            'selling_price' => $bulkSelling,
                            'discount' => rand(0, 1) ? rand(10, 50) : 0,
                        ]);
                    }
                }
            }
        }
    }
}
