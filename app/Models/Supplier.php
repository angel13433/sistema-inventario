<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'name',
        'rif',
        'phone',
        'email',
        'address',
        'contact_person',
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
     * Productos suministrados por este proveedor.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // =========================================================
    // Scopes
    // =========================================================

    /**
     * Filtra solo proveedores activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
