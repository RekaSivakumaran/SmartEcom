<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLogModel extends Model
{
    protected $table = 'inventory_logs';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reason',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}