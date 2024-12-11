<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProductEntry;
use App\Models\Product;

return new class extends Migration
{
    public function up()
    {
        // Corrigir valores nas entradas de produtos
        $entries = ProductEntry::all();
        foreach ($entries as $entry) {
            if ($entry->purchase_price > 100) { // Se o valor estiver muito alto, provavelmente está errado
                $entry->purchase_price = $entry->purchase_price / 100;
                $entry->save();
            }
        }

        // Corrigir valores nos produtos
        $products = Product::all();
        foreach ($products as $product) {
            if ($product->last_purchase_price > 100) { // Se o valor estiver muito alto, provavelmente está errado
                $product->last_purchase_price = $product->last_purchase_price / 100;
                $product->save();
            }
        }
    }

    public function down()
    {
        // Não é necessário fazer nada no down, pois isso é uma correção de dados
    }
};
