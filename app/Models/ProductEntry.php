<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'purchase_price',
        'tax_percentage',
        'freight_cost',
        'weight_kg',
        'unit_cost',
        'quantity',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'freight_cost' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        return $this->unit_cost * $this->quantity;
    }

    public function getPurchasePriceFormattedAttribute()
    {
        return number_format((float)$this->purchase_price, 2, ',', '.');
    }

    public function getTaxAmountAttribute()
    {
        return $this->purchase_price * ($this->tax_percentage / 100);
    }

    public function getFreightAmountAttribute()
    {
        return $this->freight_cost * $this->weight_kg;
    }

    public function getUnitCostAttribute()
    {
        $value = $this->attributes['unit_cost'];
        if ($value > 0) {
            return $value;
        }
        
        // Calcula o custo unitário se não estiver definido
        return $this->purchase_price + 
               $this->tax_amount + 
               $this->freight_amount;
    }

    public function getTotalFormattedAttribute()
    {
        return number_format((float)$this->total, 2, ',', '.');
    }

    public function getTaxPercentageFormattedAttribute()
    {
        return number_format((float)$this->tax_percentage, 2, ',', '.');
    }

    public function getFreightCostFormattedAttribute()
    {
        return number_format((float)$this->freight_cost, 2, ',', '.');
    }

    public function getWeightKgFormattedAttribute()
    {
        return number_format((float)$this->weight_kg, 3, ',', '.');
    }

    public function getUnitCostFormattedAttribute()
    {
        return number_format((float)$this->unit_cost, 2, ',', '.');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
