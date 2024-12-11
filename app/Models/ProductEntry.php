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
        'quantity',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
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
        return $this->purchase_price * $this->quantity;
    }

    public function getPurchasePriceFormattedAttribute()
    {
        return number_format((float)$this->purchase_price, 2, ',', '.');
    }

    public function getTotalFormattedAttribute()
    {
        return number_format((float)$this->total, 2, ',', '.');
    }

    public function setPurchasePriceAttribute($value)
    {
        if (is_string($value)) {
            // Remove todos os pontos e substitui vírgula por ponto
            $value = str_replace(['.', ','], ['', '.'], $value);
            
            // Se o valor for maior que 100, provavelmente está em centavos
            if ($value > 100) {
                $value = $value / 100;
            }
        }
        
        $this->attributes['purchase_price'] = $value;
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
