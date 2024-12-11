<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $attributes = [
        'active' => true
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
