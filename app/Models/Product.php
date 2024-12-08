<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\SaleItem;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock_quantity',
        'cost_price',
        'category_id',
        'image',
        'active',
        'min_stock'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock' => 'integer',
        'active' => 'boolean'
    ];

    protected $attributes = [
        'active' => true,
        'stock_quantity' => 0,
        'min_stock' => 5
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function reduceStock($quantity)
    {
        $this->stock_quantity -= $quantity;
        $this->save();
    }
}
