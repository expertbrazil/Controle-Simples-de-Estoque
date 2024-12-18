<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class SkuValidation implements Rule
{
    private $product;
    private $message;

    public function __construct($product = null)
    {
        $this->product = $product;
    }

    public function passes($attribute, $value)
    {
        // SKU deve ter entre 3 e 20 caracteres
        if (strlen($value) < 3 || strlen($value) > 20) {
            $this->message = 'O SKU deve ter entre 3 e 20 caracteres.';
            return false;
        }

        // SKU deve conter apenas letras, números e hífens
        if (!preg_match('/^[A-Za-z0-9\-]+$/', $value)) {
            $this->message = 'O SKU deve conter apenas letras, números e hífens.';
            return false;
        }

        // Verifica se o SKU já existe
        $query = Product::where('sku', $value);
        
        // Se estiver editando, exclui o produto atual da verificação
        if ($this->product) {
            $query->where('id', '!=', $this->product->id);
        }

        if ($query->exists()) {
            $this->message = 'Este SKU já está em uso.';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
