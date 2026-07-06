<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $fillable = [
        'cheque_no',
        'type', // 'received' or 'issued'
        'invoice_id',
        'credit_id',
        'supplier_id',
        'bank_name',
        'amount',
        'due_date',
        'status',
        'return_date'
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
