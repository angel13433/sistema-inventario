<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de productos.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode', 100)->unique()->nullable()->comment('Código de barras EAN/UPC');
            $table->string('sku', 80)->unique()->comment('Código interno de referencia del producto');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->decimal('price_usd', 10, 2)->default(0.00)->comment('Precio en dólares estadounidenses (USD)');
            $table->unsignedInteger('min_stock')->default(0)->comment('Stock mínimo antes de alerta de reposición');
            $table->integer('current_stock')->default(0)->comment('Stock actual en inventario');
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->restrictOnDelete();
            $table->foreignId('supplier_id')
                  ->nullable()
                  ->constrained('suppliers')
                  ->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices para búsquedas frecuentes
            $table->index('name');
            $table->index('is_active');
            $table->index(['current_stock', 'min_stock'], 'idx_stock_alert');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
