<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    protected $table = 'business_settings';
    protected $fillable =[
        'id',
        'type',
        'value',
        'lang',
        'created_at',
        'updated_at'
    ];
}
