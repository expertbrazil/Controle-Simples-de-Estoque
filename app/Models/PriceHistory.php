<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'purchase_price',
        'freight_cost',
        'tax_percentage',
        'unit_cost',
        'distributor_markup',
        'distributor_price',
        'consumer_markup',
        'consumer_price',
        'user_id',
        'reason',
        'entry_id'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'freight_cost' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'distributor_markup' => 'decimal:2',
        'distributor_price' => 'decimal:2',
        'consumer_markup' => 'decimal:2',
        'consumer_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relacionamentos
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function entry()
    {
        return $this->belongsTo(ProductEntry::class, 'entry_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Acessores para formatação de valores
    public function getFormattedPurchasePriceAttribute()
    {
        return 'R$ ' . number_format($this->purchase_price, 2, ',', '.');
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

    public function getFormattedTaxPercentageAttribute()
    {
        return number_format($this->tax_percentage, 2, ',', '.') . '%';
    }

    public function getFormattedFreightCostAttribute()
    {
        return 'R$ ' . number_format($this->freight_cost, 2, ',', '.');
    }

    // Acessor para obter o preço anterior
    public function getOldPriceAttribute()
    {
        $previousRecord = self::where('product_id', $this->product_id)
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        return $previousRecord ? $previousRecord->consumer_price : $this->consumer_price;
    }

    public function getFormattedOldPriceAttribute()
    {
        return 'R$ ' . number_format($this->old_price, 2, ',', '.');
    }

    // Acessor para calcular a variação percentual
    public function getPriceIncreaseAttribute()
    {
        if ($this->old_price == 0) return 0;
        return (($this->consumer_price - $this->old_price) / $this->old_price) * 100;
    }

    // Acessor para calcular a variação percentual de preço
    public function getPriceVariationAttribute()
    {
        $oldPrice = $this->old_price;
        if ($oldPrice == 0) return 0;
        
        return (($this->consumer_price - $oldPrice) / $oldPrice) * 100;
    }

    public function getFormattedPriceVariationAttribute()
    {
        $variation = $this->price_variation;
        $signal = $variation >= 0 ? '+' : '';
        return $signal . number_format($variation, 2, ',', '.') . '%';
    }

    // Escopo para obter o histórico de um produto
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId)->orderBy('created_at', 'desc');
    }

    // Escopo para obter variações de preço significativas
    public function scopeSignificantChanges($query, $percentageThreshold = 10)
    {
        return $query->whereRaw('
            ABS(
                (consumer_price - (
                    SELECT consumer_price 
                    FROM price_histories ph2 
                    WHERE ph2.product_id = price_histories.product_id 
                    AND ph2.created_at < price_histories.created_at
                    ORDER BY created_at DESC 
                    LIMIT 1
                )) / NULLIF((
                    SELECT consumer_price 
                    FROM price_histories ph2 
                    WHERE ph2.product_id = price_histories.product_id 
                    AND ph2.created_at < price_histories.created_at
                    ORDER BY created_at DESC 
                    LIMIT 1
                ), 0) * 100
            ) >= ?', [$percentageThreshold]);
    }

    // Escopo para obter registros do último mês
    public function scopeLastMonth($query)
    {
        return $query->where('created_at', '>=', now()->subMonth());
    }

    // Escopo para obter apenas os últimos registros de cada produto
    public function scopeLatestByProduct($query)
    {
        $subQuery = self::selectRaw('MAX(id) as id')
            ->groupBy('product_id');
            
        return $query->whereIn('id', $subQuery);
    }
}
