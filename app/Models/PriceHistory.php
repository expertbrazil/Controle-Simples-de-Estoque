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

    // Escopo para obter o histórico de um produto
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId)->orderBy('created_at', 'desc');
    }

    // Escopo para obter variações de preço significativas
    public function scopeSignificantChanges($query, $percentageThreshold = 10)
    {
        return $query->whereExists(function ($subquery) use ($percentageThreshold) {
            $subquery->from('price_histories as ph2')
                ->whereRaw('ph2.product_id = price_histories.product_id')
                ->whereRaw('ph2.created_at < price_histories.created_at')
                ->whereRaw('NOT EXISTS (
                    SELECT 1 FROM price_histories ph3 
                    WHERE ph3.product_id = ph2.product_id 
                    AND ph3.created_at > ph2.created_at 
                    AND ph3.created_at < price_histories.created_at
                )')
                ->whereRaw('ABS((price_histories.consumer_price - ph2.consumer_price) / ph2.consumer_price * 100) >= ?', [$percentageThreshold]);
        });
    }

    // Escopo para obter registros do último mês
    public function scopeLastMonth($query)
    {
        return $query->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)');
    }

    // Escopo para obter apenas os últimos registros de cada produto
    public function scopeLatestByProduct($query)
    {
        return $query->whereIn('id', function($subquery) {
            $subquery->select(\DB::raw('MAX(id)'))
                ->from('price_histories')
                ->groupBy('product_id');
        });
    }
}
