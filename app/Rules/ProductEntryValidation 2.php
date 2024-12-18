<?php

namespace App\Rules;

class ProductEntryValidation
{
    public static function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'entry_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ];
    }

    public static function messages()
    {
        return [
            'product_id.required' => 'O produto é obrigatório',
            'product_id.exists' => 'O produto selecionado não existe',
            'quantity.required' => 'A quantidade é obrigatória',
            'quantity.integer' => 'A quantidade deve ser um número inteiro',
            'quantity.min' => 'A quantidade deve ser pelo menos 1',
            'cost_price.required' => 'O preço de custo é obrigatório',
            'cost_price.numeric' => 'O preço de custo deve ser um número',
            'cost_price.min' => 'O preço de custo não pode ser negativo',
            'supplier_id.required' => 'O fornecedor é obrigatório',
            'supplier_id.exists' => 'O fornecedor selecionado não existe',
            'entry_date.required' => 'A data de entrada é obrigatória',
            'entry_date.date' => 'A data de entrada deve ser uma data válida',
            'invoice_number.max' => 'O número da nota fiscal não pode ter mais que 50 caracteres'
        ];
    }
}
