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
        'customer_id',
        'user_id',
        'subtotal_amount',
        'discount_percent',
        'discount_amount',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'notes',
        'completed_at',
        'cancelled_at'
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending',
        'discount_percent' => 0,
        'discount_amount' => 0,
        'subtotal_amount' => 0,
        'total_amount' => 0
    ];

    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    protected $dates = [
        'completed_at',
        'cancelled_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }

    public function markAsCancelled()
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->save();
    }

    public function calculateTotals()
    {
        $this->subtotal_amount = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->discount_amount = ($this->subtotal_amount * $this->discount_percent) / 100;
        $this->total_amount = $this->subtotal_amount - $this->discount_amount;
        
        return $this;
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->user_id) && auth()->check()) {
                $sale->user_id = auth()->id();
            }
            if (empty($sale->status)) {
                $sale->status = 'pending';
            }
        });
    }
}
