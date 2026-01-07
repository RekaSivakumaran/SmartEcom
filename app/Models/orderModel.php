<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orderModel extends Model
{
     protected $table = 'orders';
    protected $fillable = [
        'bill_no', 'name', 'email', 'mobile_number',
        'billing_address1', 'billing_address2', 'billing_city', 'billing_country', 'billing_postcode',
        'shipping_address1', 'shipping_address2', 'shipping_city', 'shipping_country', 'shipping_postcode',
        'status', 'payment_status', 'total'
    ];

    public function items()
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
