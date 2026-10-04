<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla de movimientos de inventario.
     * Registra todas las entradas, salidas y ajustes con trazabilidad completa.
     */
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->restrictOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->comment('Usuario responsable del movimiento');
            $table->enum('type', ['entry', 'exit', 'adjustment'])
                  ->comment('Tipo: entry=entrada, exit=salida, adjustment=ajuste');
            $table->integer('quantity')->comment('Cantidad del movimiento (siempre positivo; el tipo define la dirección)');
            $table->integer('previous_stock')->comment('Stock antes del movimiento (auditoría)');
            $table->integer('new_stock')->comment('Stock después del movimiento (auditoría)');
            $table->string('reason', 255)->comment('Motivo del movimiento (ej: Compra a proveedor, Venta, Merma)');
            $table->text('notes')->nullable()->comment('Observaciones adicionales opcionales');
            $table->timestamps();

            // Índices para reportes y consultas frecuentes
            $table->index('type');
            $table->index('created_at');
            $table->index(['product_id', 'created_at'], 'idx_product_movements');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
