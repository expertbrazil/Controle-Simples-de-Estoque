<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $fillable = [
        'name',
        'markup_percentage',
        'type',
        'is_active'
    ];

    protected $casts = [
        'markup_percentage' => 'decimal:2',
        'is_active' => 'boolean'
    ];
}
