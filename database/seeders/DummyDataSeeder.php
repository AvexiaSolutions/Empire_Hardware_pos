<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Item;
use App\Models\ItemBatch;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Delete old dummy data
        $oldItems = Item::where('code', 'like', 'ITM10%')->get();
        foreach ($oldItems as $item) {
            ItemBatch::where('item_id', $item->id)->delete();
            $item->delete();
        }

        $names = [
            'Wireless Mouse', 'Gaming Keyboard', 'USB C Cable', 'HDMI Cable 2m',
            'LED Bulb 10W', 'LED Tube Light', 'Extension Cord', 'Power Strip 4 Way',
            'Screwdriver Set', 'Claw Hammer', 'Drill Machine', 'Tape Measure 5m',
            'Rice 1kg', 'Sugar 1kg', 'Tea Powder 200g', 'Coffee Jar 100g',
            'Notebook A4', 'Pen Blue 5pk', 'Marker Black', 'Printer Paper A4',
            'Milk Powder 400g', 'Shampoo 200ml', 'Soap Bath', 'Toothpaste 120g'
        ];

        $categories = Category::all();
        if ($categories->isEmpty()) {
            $cat = Category::create(['name' => 'General']);
            $sub = SubCategory::create(['category_id' => $cat->id, 'name' => 'General Sub']);
        }

        $subCats = SubCategory::all();

        foreach ($names as $index => $name) {
            $sub = $subCats->random();
            
            // Random alias based on name
            $words = explode(' ', $name);
            $alias = strtolower($words[0]) . ', ' . strtolower(end($words));
            
            // Random Rack location
            $racks = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'Rack 1', 'Rack 2'];
            $rows = ['Row 1', 'Row 2', 'Row 3', 'Top Shelf', 'Bottom Shelf'];

            $item = Item::create([
                'code' => 'ITM' . str_pad($index + 1000, 4, '0', STR_PAD_LEFT),
                'name' => $name,
                'category_id' => $sub->category_id,
                'sub_category_id' => $sub->id,
                'search_aliases' => $alias,
                'rack_number' => $racks[array_rand($racks)],
                'rack_row' => $rows[array_rand($rows)],
            ]);

            ItemBatch::create([
                'item_id' => $item->id,
                'batch_no' => 'B01',
                'barcode' => $item->code . '-B01',
                'cost_price' => rand(50, 200),
                'selling_price' => rand(250, 1000),
                'quantity' => rand(10, 50),
                'is_active' => true,
            ]);
        }
    }
}
