<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Item extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    protected $fillable = [
        'code',
        'name',
        'image',
        'base_unit',
        'has_expiry_date',
        'has_warranty',
        'warranty_months',
        'category_id',
        'sub_category_id',
        'search_aliases',
        'rack_number',
        'rack_row',
        'is_loose',
        'parent_item_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function batches()
    {
        return $this->hasMany(ItemBatch::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(Item::class, 'parent_item_id');
    }

    public function looseItems()
    {
        return $this->hasMany(Item::class, 'parent_item_id');
    }

    public function getTotalStock()
    {
        return $this->batches()->where('is_active', true)->sum('quantity');
    }
}
