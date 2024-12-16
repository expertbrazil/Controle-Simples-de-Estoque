<?php

namespace App\Rules;

class ProductValidation
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:50',
            'barcode' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'last_purchase_price' => ['required', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'tax_percentage' => ['nullable', 'regex:/^\d+(?:,\d{1,2})?%?$/'],
            'freight_cost' => ['nullable', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'weight_kg' => ['nullable', 'regex:/^\d+(?:,\d{1,3})?(?:\s*kg)?$/'],
            'unit_cost' => ['nullable', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'consumer_markup' => ['nullable', 'regex:/^\d+(?:,\d{1,2})?%?$/'],
            'consumer_price' => ['nullable', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'distributor_markup' => ['nullable', 'regex:/^\d+(?:,\d{1,2})?%?$/'],
            'distributor_price' => ['nullable', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'active' => 'boolean',
            'image' => 'nullable|image|max:2048'
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'O nome do produto é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'sku.required' => 'O SKU é obrigatório',
            'sku.max' => 'O SKU não pode ter mais que 50 caracteres',
            'category_id.required' => 'A categoria é obrigatória',
            'category_id.exists' => 'A categoria selecionada não existe',
            'brand_id.required' => 'A marca é obrigatória',
            'brand_id.exists' => 'A marca selecionada não existe',
            'supplier_id.required' => 'O fornecedor é obrigatório',
            'supplier_id.exists' => 'O fornecedor selecionado não existe',
            'last_purchase_price.required' => 'O preço de compra é obrigatório',
            'last_purchase_price.regex' => 'O preço de compra deve estar no formato R$ 0,00',
            'tax_percentage.regex' => 'O percentual de impostos deve estar no formato 0,00%',
            'freight_cost.regex' => 'O custo do frete deve estar no formato R$ 0,00',
            'weight_kg.regex' => 'O peso deve estar no formato 0,000 kg',
            'unit_cost.regex' => 'O custo unitário deve estar no formato R$ 0,00',
            'consumer_markup.regex' => 'A margem de consumidor deve estar no formato 0,00%',
            'consumer_price.regex' => 'O preço para consumidor deve estar no formato R$ 0,00',
            'distributor_markup.regex' => 'A margem de distribuidor deve estar no formato 0,00%',
            'distributor_price.regex' => 'O preço para distribuidor deve estar no formato R$ 0,00',
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.max' => 'A imagem não pode ter mais que 2MB'
        ];
    }
}
