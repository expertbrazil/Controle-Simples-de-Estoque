<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class BarcodeValidation implements Rule
{
    private $product;
    private $message;

    public function __construct($product = null)
    {
        $this->product = $product;
    }

    public function passes($attribute, $value)
    {
        // Se o código de barras estiver vazio, é válido
        if (empty($value)) {
            return true;
        }

        // Código de barras deve ter entre 8 e 14 caracteres (EAN-8, EAN-13, UPC, etc)
        if (strlen($value) < 8 || strlen($value) > 14) {
            $this->message = 'O código de barras deve ter entre 8 e 14 dígitos.';
            return false;
        }

        // Código de barras deve conter apenas números
        if (!preg_match('/^[0-9]+$/', $value)) {
            $this->message = 'O código de barras deve conter apenas números.';
            return false;
        }

        // Verifica se o código de barras já existe
        $query = Product::where('barcode', $value);
        
        // Se estiver editando, exclui o produto atual da verificação
        if ($this->product) {
            $query->where('id', '!=', $this->product->id);
        }

        if ($query->exists()) {
            $this->message = 'Este código de barras já está em uso.';
            return false;
        }

        // Validação do dígito verificador para EAN-13
        if (strlen($value) === 13) {
            $checksum = 0;
            for ($i = 0; $i < 12; $i++) {
                $checksum += (($i % 2) === 0 ? 1 : 3) * intval($value[$i]);
            }
            $checkDigit = (10 - ($checksum % 10)) % 10;
            
            if (intval($value[12]) !== $checkDigit) {
                $this->message = 'Código de barras EAN-13 inválido.';
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
