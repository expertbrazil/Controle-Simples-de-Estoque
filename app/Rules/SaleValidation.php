<?php

namespace App\Rules;

class SaleValidation
{
    public static function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'payment_method' => 'required|string|in:money,credit_card,debit_card,pix',
            'payment_status' => 'required|string|in:pending,paid,cancelled',
            'installments' => 'nullable|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0'
        ];
    }

    public static function messages()
    {
        return [
            'customer_id.required' => 'O cliente é obrigatório',
            'customer_id.exists' => 'O cliente selecionado não existe',
            'sale_date.required' => 'A data da venda é obrigatória',
            'sale_date.date' => 'A data da venda deve ser uma data válida',
            'payment_method.required' => 'A forma de pagamento é obrigatória',
            'payment_method.in' => 'A forma de pagamento selecionada é inválida',
            'payment_status.required' => 'O status do pagamento é obrigatório',
            'payment_status.in' => 'O status do pagamento selecionado é inválido',
            'installments.integer' => 'O número de parcelas deve ser um número inteiro',
            'installments.min' => 'O número de parcelas deve ser pelo menos 1',
            'discount.numeric' => 'O desconto deve ser um número',
            'discount.min' => 'O desconto não pode ser negativo',
            'items.required' => 'A venda deve ter pelo menos um item',
            'items.array' => 'Os itens da venda devem ser uma lista',
            'items.min' => 'A venda deve ter pelo menos um item',
            'items.*.product_id.required' => 'O produto é obrigatório',
            'items.*.product_id.exists' => 'O produto selecionado não existe',
            'items.*.quantity.required' => 'A quantidade é obrigatória',
            'items.*.quantity.integer' => 'A quantidade deve ser um número inteiro',
            'items.*.quantity.min' => 'A quantidade deve ser pelo menos 1',
            'items.*.price.required' => 'O preço é obrigatório',
            'items.*.price.numeric' => 'O preço deve ser um número',
            'items.*.price.min' => 'O preço não pode ser negativo',
            'items.*.discount.numeric' => 'O desconto deve ser um número',
            'items.*.discount.min' => 'O desconto não pode ser negativo'
        ];
    }
}
