<?php

namespace App\Rules;

use App\Rules\SkuValidation;
use App\Rules\BarcodeValidation;

class ProductValidation
{
    public static function rules($product = null)
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sku' => ['required', new SkuValidation($product)],
            'barcode' => ['nullable', new BarcodeValidation($product)],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'max_stock' => ['required', 'integer', 'min:0', 'gte:min_stock'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'last_purchase_price' => ['required', 'numeric', 'min:0'],
            'tax_percentage' => ['required', 'numeric', 'min:0'],
            'freight_cost' => ['required', 'numeric', 'min:0'],
            'weight_kg' => ['required', 'numeric', 'min:0'],
            'consumer_markup' => ['required', 'numeric', 'min:0'],
            'distributor_markup' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'consumer_price' => ['required', 'numeric', 'min:0'],
            'distributor_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['boolean']
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.max' => 'O nome do produto não pode ter mais de 255 caracteres.',
            'sku.required' => 'O SKU é obrigatório.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada é inválida.',
            'brand_id.required' => 'A marca é obrigatória.',
            'brand_id.exists' => 'A marca selecionada é inválida.',
            'supplier_id.required' => 'O fornecedor é obrigatório.',
            'supplier_id.exists' => 'O fornecedor selecionado é inválido.',
            'min_stock.required' => 'O estoque mínimo é obrigatório.',
            'min_stock.integer' => 'O estoque mínimo deve ser um número inteiro.',
            'min_stock.min' => 'O estoque mínimo não pode ser negativo.',
            'max_stock.required' => 'O estoque máximo é obrigatório.',
            'max_stock.integer' => 'O estoque máximo deve ser um número inteiro.',
            'max_stock.min' => 'O estoque máximo não pode ser negativo.',
            'max_stock.gte' => 'O estoque máximo deve ser maior ou igual ao estoque mínimo.',
            'stock_quantity.required' => 'A quantidade em estoque é obrigatória.',
            'stock_quantity.integer' => 'A quantidade em estoque deve ser um número inteiro.',
            'stock_quantity.min' => 'A quantidade em estoque não pode ser negativa.',
            'last_purchase_price.required' => 'O preço de compra é obrigatório.',
            'last_purchase_price.numeric' => 'O preço de compra deve ser um número.',
            'last_purchase_price.min' => 'O preço de compra não pode ser negativo.',
            'tax_percentage.required' => 'O percentual de impostos é obrigatório.',
            'tax_percentage.numeric' => 'O percentual de impostos deve ser um número.',
            'tax_percentage.min' => 'O percentual de impostos não pode ser negativo.',
            'freight_cost.required' => 'O custo de frete é obrigatório.',
            'freight_cost.numeric' => 'O custo de frete deve ser um número.',
            'freight_cost.min' => 'O custo de frete não pode ser negativo.',
            'weight_kg.required' => 'O peso é obrigatório.',
            'weight_kg.numeric' => 'O peso deve ser um número.',
            'weight_kg.min' => 'O peso não pode ser negativo.',
            'consumer_markup.required' => 'A margem de lucro para consumidor é obrigatória.',
            'consumer_markup.numeric' => 'A margem de lucro para consumidor deve ser um número.',
            'consumer_markup.min' => 'A margem de lucro para consumidor não pode ser negativa.',
            'distributor_markup.required' => 'A margem de lucro para distribuidor é obrigatória.',
            'distributor_markup.numeric' => 'A margem de lucro para distribuidor deve ser um número.',
            'distributor_markup.min' => 'A margem de lucro para distribuidor não pode ser negativa.',
            'unit_cost.required' => 'O custo unitário é obrigatório.',
            'unit_cost.numeric' => 'O custo unitário deve ser um número.',
            'unit_cost.min' => 'O custo unitário não pode ser negativo.',
            'consumer_price.required' => 'O preço para consumidor é obrigatório.',
            'consumer_price.numeric' => 'O preço para consumidor deve ser um número.',
            'consumer_price.min' => 'O preço para consumidor não pode ser negativo.',
            'distributor_price.required' => 'O preço para distribuidor é obrigatório.',
            'distributor_price.numeric' => 'O preço para distribuidor deve ser um número.',
            'distributor_price.min' => 'O preço para distribuidor não pode ser negativo.',
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.max' => 'A imagem não pode ter mais de 2MB.',
            'status.boolean' => 'O status deve ser um valor booleano.'
        ];
    }
}
