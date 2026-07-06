<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'emp_id',
        'name',
        'phone',
        'designation',
        'basic_salary'
    ];
}
