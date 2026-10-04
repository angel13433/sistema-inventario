<?php

namespace App\Services;

use App\Enums\MovementType;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    /**
     * Registra una ENTRADA de stock para un producto.
     * Suma la cantidad al stock actual y crea el movimiento.
     *
     * @throws \Throwable
     */
    public function registerEntry(Product $product, int $quantity, string $reason, User $user, ?string $notes = null): InventoryMovement
    {
        return DB::transaction(function () use ($product, $quantity, $reason, $user, $notes) {
            $previousStock = $product->current_stock;
            $newStock      = $previousStock + $quantity;

            $product->update(['current_stock' => $newStock]);

            return InventoryMovement::create([
                'product_id'     => $product->id,
                'user_id'        => $user->id,
                'type'           => MovementType::Entry->value,
                'quantity'       => $quantity,
                'previous_stock' => $previousStock,
                'new_stock'      => $newStock,
                'reason'         => $reason,
                'notes'          => $notes,
            ]);
        });
    }

    /**
     * Registra una SALIDA de stock para un producto.
     * Resta la cantidad al stock actual (valida que haya suficiente stock).
     *
     * @throws ValidationException|\Throwable
     */
    public function registerExit(Product $product, int $quantity, string $reason, User $user, ?string $notes = null): InventoryMovement
    {
        if ($product->current_stock < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => [
                    "Stock insuficiente. Stock disponible: {$product->current_stock} unidades.",
                ],
            ]);
        }

        return DB::transaction(function () use ($product, $quantity, $reason, $user, $notes) {
            $previousStock = $product->current_stock;
            $newStock      = $previousStock - $quantity;

            $product->update(['current_stock' => $newStock]);

            return InventoryMovement::create([
                'product_id'     => $product->id,
                'user_id'        => $user->id,
                'type'           => MovementType::Exit->value,
                'quantity'       => $quantity,
                'previous_stock' => $previousStock,
                'new_stock'      => $newStock,
                'reason'         => $reason,
                'notes'          => $notes,
            ]);
        });
    }

    /**
     * Registra un AJUSTE de stock para un producto.
     * Establece el stock a un valor absoluto (para correcciones de inventario físico).
     *
     * @throws \Throwable
     */
    public function adjustStock(Product $product, int $newQuantity, string $reason, User $user, ?string $notes = null): InventoryMovement
    {
        return DB::transaction(function () use ($product, $newQuantity, $reason, $user, $notes) {
            $previousStock  = $product->current_stock;
            $adjustedAmount = abs($newQuantity - $previousStock);

            $product->update(['current_stock' => $newQuantity]);

            return InventoryMovement::create([
                'product_id'     => $product->id,
                'user_id'        => $user->id,
                'type'           => MovementType::Adjustment->value,
                'quantity'       => $adjustedAmount,
                'previous_stock' => $previousStock,
                'new_stock'      => $newQuantity,
                'reason'         => $reason,
                'notes'          => $notes,
            ]);
        });
    }
}
