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
            'last_purchase_price' => ['required', 'string'],
            'tax_percentage' => ['required', 'string'],
            'freight_cost' => ['required', 'string'],
            'weight_kg' => ['required', 'string'],
            'consumer_markup' => ['required', 'string'],
            'distributor_markup' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048']
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
            'tax_percentage.required' => 'O percentual de impostos é obrigatório.',
            'freight_cost.required' => 'O custo de frete é obrigatório.',
            'weight_kg.required' => 'O peso é obrigatório.',
            'consumer_markup.required' => 'A margem de lucro para consumidor é obrigatória.',
            'distributor_markup.required' => 'A margem de lucro para distribuidor é obrigatória.',
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.max' => 'A imagem não pode ter mais de 2MB.'
        ];
    }
}
