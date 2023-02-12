<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailContact extends Model
{
    protected $table = 'email_contacts';
    protected $fillable =[
        'id',
        'email',
        'phone',
        'welcome',
        'register',
        'preOrder',
        'created_at',
        'updated_at'
    ];
}
