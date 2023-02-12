<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount_code extends Model
{
    protected $table = 'discount_codes';
    protected $fillable =[
        'id',
        'code',
        'expired',
        'start_date',
        'type',
        'apply_for_order',
        'status',
        'percent',
        'fixed_price',
        'created_at',
        'updated_at',
        'used_at'
    ];

    const status = [
        'Inactive' => 0,
        'Active' => 1,
    ];

    const Type = [
        'OneTime' => 0,
        'Time' => 1
    ];
}
