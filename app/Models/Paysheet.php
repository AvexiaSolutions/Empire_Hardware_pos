<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paysheet extends Model
{
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
