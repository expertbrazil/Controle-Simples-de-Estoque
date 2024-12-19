<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    protected static function booted()
    {
        static::addGlobalScope('customer', function ($query) {
            $query->whereJsonContains('flag', 'cliente');
        });
    }

    protected $fillable = [
        'status',
        'tipo_pessoa',
        'nome',
        'razao_social',
        'documento',
        'phone',
        'whatsapp',
        'email',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'nome_contato',
        'flag',
        'usuario',
        'senha',
        'observacoes',
        'inscricao_estadual',
        'inscricao_municipal'
    ];

    protected $casts = [
        'flag' => 'array',
        'status' => 'boolean'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'supplier_id');
    }
}
