<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImportHistory extends Model
{
    protected $table = 'product_import_histories';
    protected $fillable =[
        'id',
        'filename',
        'file_location',
        'process',
        'message',
        'created_at',
        'updated_at'
    ];
}
