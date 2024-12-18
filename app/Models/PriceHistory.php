<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $table = 'price_history';

    protected $fillable = [
        'product_id',
        'last_purchase_price',
        'unit_cost',
        'consumer_price',
        'distributor_price',
        'change_reason',
        'user_id'
    ];

    protected $casts = [
        'last_purchase_price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'consumer_price' => 'decimal:2',
        'distributor_price' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
