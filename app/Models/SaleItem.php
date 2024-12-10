<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Sale;
use App\Models\Product;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'discount_percent',
        'discount_amount',
        'total'
    ];

    protected $attributes = [
        'discount_percent' => 0,
        'discount_amount' => 0
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateTotal()
    {
        $subtotal = $this->quantity * $this->price;
        $this->discount_amount = ($subtotal * $this->discount_percent) / 100;
        $this->total = $subtotal - $this->discount_amount;
        return $this->total;
    }
}
