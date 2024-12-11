<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cnpj',
        'phone',
        'whatsapp',
        'email',
        'address',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'contact_name',
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

    public function getFormattedPhoneAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (strlen($phone) === 11) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
        }
        return $this->phone;
    }

    public function getFormattedWhatsappAttribute()
    {
        $whatsapp = preg_replace('/[^0-9]/', '', $this->whatsapp);
        if (strlen($whatsapp) === 11) {
            return '(' . substr($whatsapp, 0, 2) . ') ' . substr($whatsapp, 2, 5) . '-' . substr($whatsapp, 7);
        }
        return $this->whatsapp;
    }

    public function getFormattedCnpjAttribute()
    {
        $cnpj = preg_replace('/[^0-9]/', '', $this->cnpj);
        if (strlen($cnpj) === 14) {
            return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12);
        }
        return $this->cnpj;
    }
}
