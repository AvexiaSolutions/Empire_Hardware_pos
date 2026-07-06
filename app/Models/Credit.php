<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'type', // 'received' or 'issued'
        'invoice_id',
        'supplier_id',
        'amount',
        'due_date',
        'status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
