<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleDetail extends Model
{
    protected $table = 'role_details';
    protected $fillable = [
        'id',
        'module_id',
        'role_id',
        'permission',
        'created_at',
        'updated_at'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
