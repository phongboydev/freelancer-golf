<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViettelPostDistrict extends Model
{
    protected $table = 'viettel_post_districts';
    protected $fillable =[
        'id',
        'province_id',
        'name',
        'value',
        'created_at',
        'updated_at'
    ];
}
