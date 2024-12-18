<?php

namespace App\Rules;

class ProductValidation
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gt:min_stock',
            'stock_quantity' => 'required|integer|min:0',
            'last_purchase_price' => ['required', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'tax_percentage' => ['required', 'regex:/^\d+(?:,\d{1,2})?%?$/'],
            'freight_cost' => ['required', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'weight_kg' => ['required', 'regex:/^\d+(?:,\d{1,3})?(?:\s*kg)?$/'],
            'unit_cost' => ['nullable', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'consumer_markup' => ['required', 'regex:/^\d+(?:,\d{1,2})?%?$/'],
            'consumer_price' => ['required', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'distributor_markup' => ['required', 'regex:/^\d+(?:,\d{1,2})?%?$/'],
            'distributor_price' => ['required', 'regex:/^R\$\s*\d+(?:\.\d{3})*(?:,\d{2})?$/'],
            'active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'stored_image' => 'nullable|string'
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'O nome do produto é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'sku.required' => 'O SKU é obrigatório',
            'sku.max' => 'O SKU não pode ter mais que 50 caracteres',
            'sku.unique' => 'Este SKU já está em uso',
            'category_id.required' => 'A categoria é obrigatória',
            'category_id.exists' => 'A categoria selecionada não existe',
            'brand_id.required' => 'A marca é obrigatória',
            'brand_id.exists' => 'A marca selecionada não existe',
            'supplier_id.required' => 'O fornecedor é obrigatório',
            'supplier_id.exists' => 'O fornecedor selecionado não existe',
            'min_stock.required' => 'O estoque mínimo é obrigatório',
            'min_stock.integer' => 'O estoque mínimo deve ser um número inteiro',
            'min_stock.min' => 'O estoque mínimo não pode ser negativo',
            'max_stock.required' => 'O estoque máximo é obrigatório',
            'max_stock.integer' => 'O estoque máximo deve ser um número inteiro',
            'max_stock.min' => 'O estoque máximo não pode ser negativo',
            'max_stock.gt' => 'O estoque máximo deve ser maior que o estoque mínimo',
            'stock_quantity.required' => 'O estoque atual é obrigatório',
            'stock_quantity.integer' => 'O estoque atual deve ser um número inteiro',
            'stock_quantity.min' => 'O estoque atual não pode ser negativo',
            'last_purchase_price.required' => 'O preço de compra é obrigatório',
            'last_purchase_price.regex' => 'O preço de compra deve estar no formato R$ 0,00',
            'tax_percentage.required' => 'O percentual de impostos é obrigatório',
            'tax_percentage.regex' => 'O percentual de impostos deve estar no formato 0,00%',
            'freight_cost.required' => 'O custo do frete é obrigatório',
            'freight_cost.regex' => 'O custo do frete deve estar no formato R$ 0,00',
            'weight_kg.required' => 'O peso é obrigatório',
            'weight_kg.regex' => 'O peso deve estar no formato 0,000 kg',
            'unit_cost.regex' => 'O custo unitário deve estar no formato R$ 0,00',
            'consumer_markup.required' => 'A margem de consumidor é obrigatória',
            'consumer_markup.regex' => 'A margem de consumidor deve estar no formato 0,00%',
            'consumer_price.required' => 'O preço para consumidor é obrigatório',
            'consumer_price.regex' => 'O preço para consumidor deve estar no formato R$ 0,00',
            'distributor_markup.required' => 'A margem de distribuidor é obrigatória',
            'distributor_markup.regex' => 'A margem de distribuidor deve estar no formato 0,00%',
            'distributor_price.required' => 'O preço para distribuidor é obrigatório',
            'distributor_price.regex' => 'O preço para distribuidor deve estar no formato R$ 0,00',
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.max' => 'A imagem não pode ter mais que 2MB'
        ];
    }
}
