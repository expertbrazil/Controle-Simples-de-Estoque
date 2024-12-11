<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document', 14)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip_code', 9)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cnpj', 18)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip_code', 9)->nullable();
            $table->string('contact_name')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });

        // Brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('image')->nullable();
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->decimal('min_price', 10, 2)->default(0);
            $table->decimal('markup_percentage', 5, 2)->default(0);
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->decimal('min_stock', 10, 2)->default(0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->decimal('last_purchase_price', 10, 2)->nullable();
            $table->date('last_purchase_date')->nullable();
        });

        // Sales
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->integer('installments')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Sale Items
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });

        // Product Entries
        Schema::create('product_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->string('type'); // entrada ou saída
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });

        // Parameters
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('product_entries');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('parameters');
    }
};
