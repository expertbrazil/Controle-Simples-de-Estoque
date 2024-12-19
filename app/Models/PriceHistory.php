<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceHistory extends Model
{
    use HasFactory;

    protected $table = 'price_histories';

    protected $fillable = [
        'product_id',
        'user_id',
        'unit_cost',
        'consumer_price',
        'distributor_price',
        'reason'
    ];

    protected $casts = [
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

    public function getFormattedUnitCostAttribute()
    {
        return 'R$ ' . number_format($this->unit_cost, 2, ',', '.');
    }

    public function getFormattedConsumerPriceAttribute()
    {
        return 'R$ ' . number_format($this->consumer_price, 2, ',', '.');
    }

    public function getFormattedDistributorPriceAttribute()
    {
        return 'R$ ' . number_format($this->distributor_price, 2, ',', '.');
    }
}
