<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $attributes = [
        'active' => true
    ];

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the children categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all parent categories
     */
    public static function getParentCategories()
    {
        return static::whereNull('parent_id')
                    ->where('active', true)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Get formatted name with parent
     */
    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
