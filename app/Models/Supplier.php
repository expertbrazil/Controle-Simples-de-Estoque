<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    const PESSOA_FISICA = 'F';
    const PESSOA_JURIDICA = 'J';

    protected $fillable = [
        'nome',
        'tipo_pessoa',
        'documento',
        'email',
        'phone',
        'whatsapp',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => true,
        'tipo_pessoa' => self::PESSOA_JURIDICA
    ];

    // Formatação do documento (CPF/CNPJ)
    public function getFormattedDocumentoAttribute()
    {
        $doc = preg_replace('/[^0-9]/', '', $this->documento);
        
        // Se for pessoa física (CPF)
        if ($this->tipo_pessoa === self::PESSOA_FISICA) {
            if (strlen($doc) !== 11) return $this->documento;
            return substr($doc, 0, 3) . '.' . 
                   substr($doc, 3, 3) . '.' . 
                   substr($doc, 6, 3) . '-' . 
                   substr($doc, 9, 2);
        }
        
        // Se for pessoa jurídica (CNPJ)
        if (strlen($doc) !== 14) return $this->documento;
        return substr($doc, 0, 2) . '.' . 
               substr($doc, 2, 3) . '.' . 
               substr($doc, 5, 3) . '/' . 
               substr($doc, 8, 4) . '-' . 
               substr($doc, 12, 2);
    }

    // Relacionamentos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Formatação do telefone
    public function getFormattedPhoneAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        $length = strlen($phone);

        if ($length === 11) {
            return '(' . substr($phone, 0, 2) . ') ' . 
                   substr($phone, 2, 5) . '-' . 
                   substr($phone, 7);
        }

        if ($length === 10) {
            return '(' . substr($phone, 0, 2) . ') ' . 
                   substr($phone, 2, 4) . '-' . 
                   substr($phone, 6);
        }

        return $phone;
    }

    // Formatação do WhatsApp
    public function getFormattedWhatsappAttribute()
    {
        $whatsapp = preg_replace('/[^0-9]/', '', $this->whatsapp);
        return '(' . substr($whatsapp, 0, 2) . ') ' . 
               substr($whatsapp, 2, 5) . '-' . 
               substr($whatsapp, 7);
    }

    // Formatação do CEP
    public function getFormattedCepAttribute()
    {
        $cep = preg_replace('/[^0-9]/', '', $this->cep);
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }

    // Endereço completo
    public function getFullAddressAttribute()
    {
        $address = $this->rua;
        if ($this->numero) $address .= ', ' . $this->numero;
        if ($this->complemento) $address .= ' - ' . $this->complemento;
        if ($this->bairro) $address .= ', ' . $this->bairro;
        $address .= ' - ' . $this->cidade . '/' . $this->uf;
        if ($this->cep) $address .= ' - CEP: ' . $this->getFormattedCepAttribute();
        
        return $address;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    public function scopePessoaFisica($query)
    {
        return $query->where('tipo_pessoa', self::PESSOA_FISICA);
    }

    public function scopePessoaJuridica($query)
    {
        return $query->where('tipo_pessoa', self::PESSOA_JURIDICA);
    }
}
