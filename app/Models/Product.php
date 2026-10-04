<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'barcode',
        'sku',
        'name',
        'description',
        'price_usd',
        'min_stock',
        'current_stock',
        'category_id',
        'supplier_id',
        'is_active',
    ];

    /**
     * Castings de atributos.
     */
    protected function casts(): array
    {
        return [
            'price_usd'     => 'decimal:2',
            'min_stock'     => 'integer',
            'current_stock' => 'integer',
            'is_active'     => 'boolean',
        ];
    }

    // =========================================================
    // Relaciones
    // =========================================================

    /**
     * Categoría a la que pertenece el producto.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Proveedor del producto.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Historial de movimientos de inventario de este producto.
     */
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // =========================================================
    // Scopes
    // =========================================================

    /**
     * Filtra solo productos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filtra productos cuyo stock actual es menor o igual al stock mínimo.
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'min_stock');
    }

    // =========================================================
    // Accesorios
    // =========================================================

    /**
     * Indica si el producto está en nivel de alerta de stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }
}
