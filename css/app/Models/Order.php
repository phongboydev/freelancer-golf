<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable =[
        'id',
        'user_id',
        'email',
        'first_name',
        'last_name',
        'phone',
        'address_id',
        'address',
        'zipcode',
        'subtotal',
        'shipping_fee',
        'tax',
        'total',
        'currency',
        'discount_data',
        'code',
        'status',
        'note',
        'admin_note',
        'created_at',
        'updated_at'
    ];

    const Status = [
        'New' => 0,
        'Confirm' => 1,
        'Completed' => 2,
        'Cancel' => 3
    ];

    public function order_details() {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function address() {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
}
