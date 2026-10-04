<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Castings de atributos.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // =========================================================
    // Relaciones
    // =========================================================

    /**
     * Productos que pertenecen a esta categoría.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // =========================================================
    // Scopes
    // =========================================================

    /**
     * Filtra solo categorías activas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
