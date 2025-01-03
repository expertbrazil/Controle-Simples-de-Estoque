<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\SaleItem;
use App\Models\ProductEntry;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\PriceHistory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'barcode',
        'category_id',
        'brand_id',
        'supplier_id',
        'min_stock',
        'max_stock',
        'stock_quantity',
        'last_purchase_price',
        'tax_percentage',
        'freight_cost',
        'weight_kg',
        'unit_cost',
        'consumer_markup',
        'consumer_price',
        'distributor_markup',
        'distributor_price',
        'image',
        'status'
    ];

    protected $casts = [
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'stock_quantity' => 'integer',
        'last_purchase_price' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'freight_cost' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'consumer_markup' => 'decimal:2',
        'consumer_price' => 'decimal:2',
        'distributor_markup' => 'decimal:2',
        'distributor_price' => 'decimal:2',
        'status' => 'boolean'
    ];

    protected $attributes = [
        'stock_quantity' => 0,
        'min_stock' => 0,
        'max_stock' => 0,
        'last_purchase_price' => '0.00',
        'tax_percentage' => '0.00',
        'freight_cost' => '0.00',
        'weight_kg' => '0.000',
        'unit_cost' => '0.00',
        'consumer_markup' => '0.00',
        'consumer_price' => '0.00',
        'distributor_markup' => '0.00',
        'distributor_price' => '0.00',
        'status' => true
    ];

    protected $appends = [
        'image_url',
        'formatted_consumer_price',
        'formatted_distributor_price',
        'formatted_last_purchase_price',
        'formatted_weight',
        'supplier_name'
    ];

    // Relacionamentos
    public function category()
    {
        return $this->belongsTo(Category::class)
            ->withDefault([
                'name' => 'Sem categoria'
            ]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)
            ->withDefault([
                'name' => 'Sem marca'
            ]);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)
            ->withDefault([
                'nome_display' => 'Fornecedor não encontrado'
            ]);
    }

    public function priceHistory()
    {
        return $this->hasMany(PriceHistory::class)->orderBy('created_at', 'desc');
    }

    public function sales()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function entries()
    {
        return $this->hasMany(ProductEntry::class);
    }

    public function lastEntry()
    {
        return $this->hasOne(ProductEntry::class)->latest();
    }

    // Acessor para exibir o nome correto do fornecedor baseado no tipo (PF ou PJ)
    public function getSupplierNameAttribute()
    {
        if (!$this->supplier) {
            return 'Fornecedor não encontrado';
        }

        return $this->supplier->tipo_pessoa === 'PF' 
            ? $this->supplier->nome_completo 
            : $this->supplier->razao_social;
    }

    // Acessores para formatação de valores
    public function getFormattedConsumerPriceAttribute()
    {
        return 'R$ ' . number_format($this->consumer_price, 2, ',', '.');
    }

    public function getFormattedDistributorPriceAttribute()
    {
        return 'R$ ' . number_format($this->distributor_price, 2, ',', '.');
    }

    public function getFormattedLastPurchasePriceAttribute()
    {
        return 'R$ ' . number_format($this->last_purchase_price, 2, ',', '.');
    }

    public function getFormattedWeightAttribute()
    {
        return number_format($this->weight_kg, 3, ',', '.') . ' kg';
    }

    public function getImageUrlAttribute()
    {
        return $this->image 
            ? "/images/produtos/{$this->image}" 
            : "/images/nova_rosa_callback_ok.webp";
    }

    // Métodos
    public function calculateConsumerPrice($unitCost)
    {
        return $unitCost * (1 + ($this->consumer_markup / 100));
    }

    public function calculateDistributorPrice($unitCost)
    {
        return $unitCost * (1 + ($this->distributor_markup / 100));
    }

    public function updateStock($quantity, $type = 'add')
    {
        if ($type === 'add') {
            $this->stock_quantity += $quantity;
        } else {
            $this->stock_quantity -= $quantity;
        }
        $this->save();
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->min_stock;
    }

    public function isOverStock()
    {
        return $this->stock_quantity >= $this->max_stock;
    }

    public function calculateUnitCost()
    {
        $taxAmount = $this->last_purchase_price * ($this->tax_percentage / 100);
        return $this->last_purchase_price + $taxAmount + $this->freight_cost;
    }
}
