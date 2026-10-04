<?php

namespace App\Models;

use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'reason',
        'notes',
    ];

    /**
     * Castings de atributos.
     */
    protected function casts(): array
    {
        return [
            'type'           => MovementType::class,
            'quantity'       => 'integer',
            'previous_stock' => 'integer',
            'new_stock'      => 'integer',
        ];
    }

    // =========================================================
    // Relaciones
    // =========================================================

    /**
     * Producto al que pertenece este movimiento.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Usuario responsable del movimiento.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================
    // Scopes
    // =========================================================

    /**
     * Filtra por tipo de movimiento.
     */
    public function scopeOfType($query, MovementType $type)
    {
        return $query->where('type', $type->value);
    }

    /**
     * Filtra movimientos del último mes.
     */
    public function scopeLastMonth($query)
    {
        return $query->where('created_at', '>=', now()->subMonth());
    }
}
