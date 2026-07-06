<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ReturnLog extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'invoice_id',
        'invoice_item_id',
        'item_id',
        'item_batch_id',
        'type', // damage, change, warranty_claim
        'quantity',
        'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemBatch()
    {
        return $this->belongsTo(ItemBatch::class);
    }
}
