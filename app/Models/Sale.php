<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\SaleItem;
use App\Models\Customer;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total_amount', 
        'discount', 
        'final_amount', 
        'payment_method', 
        'status', 
        'notes', 
        'user_id',
        'customer_id',
        'discount_type',
        'discount_value'
    ];

    protected $attributes = [
        'status' => 'completed',
        'discount' => 0,
        'final_amount' => 0
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'discount_value' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function calculateTotalAmount()
    {
        $this->total_amount = $this->items->sum('total_price');
        
        // Calcula o desconto
        if ($this->discount_type === 'percentage' && $this->discount_value > 0) {
            $this->discount = ($this->total_amount * $this->discount_value) / 100;
        } elseif ($this->discount_type === 'fixed' && $this->discount_value > 0) {
            $this->discount = $this->discount_value;
        }
        
        $this->final_amount = $this->total_amount - $this->discount;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->user_id) && auth()->check()) {
                $sale->user_id = auth()->id();
            }
            if (empty($sale->status)) {
                $sale->status = 'completed';
            }
            if ($sale->total_amount > 0 && empty($sale->final_amount)) {
                $sale->final_amount = $sale->total_amount;
            }
        });
    }
}
