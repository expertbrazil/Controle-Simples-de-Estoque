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
        'active'
    ];

    protected $casts = [
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'last_purchase_price' => 'float',
        'tax_percentage' => 'float',
        'freight_cost' => 'float',
        'weight_kg' => 'float',
        'unit_cost' => 'float',
        'consumer_markup' => 'float',
        'consumer_price' => 'float',
        'distributor_markup' => 'float',
        'distributor_price' => 'float',
        'last_purchase_date' => 'datetime',
        'stock_quantity' => 'integer',
        'minimum_stock' => 'integer',
        'active' => 'boolean'
    ];

    protected $attributes = [
        'stock_quantity' => 0,
        'minimum_stock' => 5,
        'consumer_markup' => '0.00',
        'consumer_price' => '0.00',
        'distributor_markup' => '0.00',
        'distributor_price' => '0.00',
        'tax_percentage' => '0.00',
        'freight_cost' => '0.00',
        'weight_kg' => '0.000',
        'unit_cost' => '0.00',
        'last_purchase_price' => '0.00',
        'active' => true
    ];

    protected $appends = [
        'image_url', 
        'calculated_consumer_price',
        'calculated_distributor_price'
    ];

    // Relacionamentos
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sales()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function entries()
    {
        return $this->hasMany(ProductEntry::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        return asset('images/no-image.png');
    }

    public function setImageAttribute($value)
    {
        if ($value && is_file($value)) {
            if ($this->image && Storage::disk('public')->exists($this->image)) {
                Storage::disk('public')->delete($this->image);
            }
            $this->attributes['image'] = $value->store('products', 'public');
        }
    }

    // Mutator para garantir que o preço da última compra seja salvo corretamente
    public function setLastPurchasePriceAttribute($value)
    {
        if (is_string($value)) {
            $value = str_replace(['.', ','], ['', '.'], $value);
        }
        $this->attributes['last_purchase_price'] = $value;
    }

    // Accessor para formatar o preço da última compra
    public function getLastPurchasePriceFormattedAttribute()
    {
        return number_format($this->last_purchase_price, 2, ',', '.');
    }

    // Calcula o custo por unidade (preço de compra + impostos + frete)
    public function calculateUnitCost()
    {
        // Preço de compra (último preço de compra)
        $purchasePrice = $this->last_purchase_price ?? 0;
        
        // Valor dos impostos
        $taxAmount = $purchasePrice * ($this->tax_percentage / 100);
        
        // Custo do frete por unidade (se tiver peso)
        $freightPerUnit = $this->weight_kg > 0 ? ($this->freight_cost / $this->weight_kg) : 0;
        
        // Custo total por unidade
        return $purchasePrice + $taxAmount + $freightPerUnit;
    }

    // Calcula o preço consumidor baseado no markup
    public function calculateConsumerPrice()
    {
        $unitCost = $this->unit_cost;
        if ($unitCost > 0 && $this->consumer_markup > 0) {
            return $unitCost * (1 + ($this->consumer_markup / 100));
        }
        return $this->consumer_price;
    }

    // Calcula o preço distribuidor baseado no markup
    public function calculateDistributorPrice()
    {
        $unitCost = $this->unit_cost;
        if ($unitCost > 0 && $this->distributor_markup > 0) {
            return $unitCost * (1 + ($this->distributor_markup / 100));
        }
        return $this->distributor_price;
    }

    // Accessors para os preços calculados
    public function getCalculatedConsumerPriceAttribute()
    {
        return $this->calculateConsumerPrice();
    }

    public function getCalculatedDistributorPriceAttribute()
    {
        return $this->calculateDistributorPrice();
    }

    // Atualiza o custo unitário e todos os preços
    public function updatePrices()
    {
        $this->unit_cost = $this->calculateUnitCost();
        $this->consumer_price = $this->calculateConsumerPrice();
        $this->distributor_price = $this->calculateDistributorPrice();
        return $this->save();
    }
}
