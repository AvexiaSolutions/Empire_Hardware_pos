<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBatch extends Model
{
    protected $fillable = [
        'item_id',
        'batch_no',
        'barcode',
        'cost_price',
        'selling_price',
        'bulk_cost_price',
        'bulk_selling_price',
        'quantity',
        'damaged_quantity',
        'is_active',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getCostPriceAttribute($value)
    {
        if (Setting::get('fake_cost_markup_active') == '1') {
            $type = Setting::get('fake_cost_markup_type', 'percentage');
            $markup = floatval(Setting::get('fake_cost_markup_value', '0'));
            
            if ($type === 'percentage') {
                return $value + ($value * ($markup / 100));
            } else {
                return $value + $markup;
            }
        }
        return $value;
    }

    public function getBulkCostPriceAttribute($value)
    {
        if (Setting::get('fake_cost_markup_active') == '1') {
            $type = Setting::get('fake_cost_markup_type', 'percentage');
            $markup = floatval(Setting::get('fake_cost_markup_value', '0'));
            
            if ($type === 'percentage') {
                return $value + ($value * ($markup / 100));
            } else {
                return $value + $markup;
            }
        }
        return $value;
    }
}
