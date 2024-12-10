<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Primeiro fazemos backup dos dados existentes
        if (Schema::hasTable('sales')) {
            DB::statement('CREATE TABLE sales_backup AS SELECT * FROM sales');
            Schema::dropIfExists('sale_items');
            Schema::dropIfExists('sales');
        }

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained();
            
            // Valores monetários
            $table->decimal('subtotal_amount', 10, 2)->default(0);
            $table->decimal('discount_percent', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Status e método de pagamento
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->string('payment_method')->nullable(); // money, credit, debit, pix
            $table->string('payment_status')->default('pending'); // pending, paid, refunded
            
            // Campos adicionais
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Timestamps padrão
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_percent', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        // Restaura os dados do backup se existir
        if (Schema::hasTable('sales_backup')) {
            DB::statement('INSERT INTO sales (id, customer_id, user_id, subtotal_amount, discount_percent, discount_amount, total_amount, status, payment_method, payment_status, created_at, updated_at, deleted_at) SELECT id, customer_id, user_id, COALESCE(subtotal_amount, 0), COALESCE(discount_percent, 0), COALESCE(discount_amount, 0), COALESCE(total_amount, 0), COALESCE(status, "completed"), COALESCE(payment_method, "money"), COALESCE(payment_status, "paid"), created_at, updated_at, deleted_at FROM sales_backup');
            Schema::dropIfExists('sales_backup');
        }
    }

    public function down()
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
