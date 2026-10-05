<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Prepara los datos del panel de control de inventario
 * (métricas, listado de productos, alertas y categorías).
 */
class DashboardService
{
    /**
     * Estados posibles de stock de un producto.
     */
    public const STATUS_IN_STOCK = 'in_stock';
    public const STATUS_LOW_STOCK = 'low_stock';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';

    /**
     * Devuelve todos los datos necesarios para renderizar el dashboard.
     */
    public function getDashboardData(float $bcvRate): array
    {
        $products = $this->getProducts();

        return [
            'metrics'    => $this->buildMetrics($products, $bcvRate),
            'products'   => $products->values(),
            'alerts'     => $this->buildAlerts($products),
            'categories' => Category::active()->orderBy('name')->get(['id', 'name']),
            'bcvRate'    => $bcvRate,
        ];
    }

    /**
     * Obtiene los productos activos ya normalizados para la vista.
     */
    private function getProducts(): Collection
    {
        return Product::with(['category:id,name', 'supplier:id,name'])
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'price_usd', 'current_stock', 'min_stock', 'category_id', 'supplier_id'])
            ->map(fn (Product $product) => [
                'id'            => $product->id,
                'name'          => $product->name,
                'sku'           => $product->sku,
                'category_id'   => $product->category_id,
                'category'      => $product->category?->name,
                'supplier'      => $product->supplier?->name,
                'price_usd'     => (float) $product->price_usd,
                'current_stock' => (int) $product->current_stock,
                'min_stock'     => (int) $product->min_stock,
                'status'        => $this->resolveStatus($product),
            ]);
    }

    /**
     * Calcula las métricas de las tarjetas de resumen.
     */
    private function buildMetrics(Collection $products, float $bcvRate): array
    {
        $inventoryValue = $products->sum(fn (array $p) => $p['price_usd'] * max($p['current_stock'], 0));

        return [
            'total_products'      => $products->count(),
            'total_units'         => $products->sum(fn (array $p) => max($p['current_stock'], 0)),
            'low_stock_count'     => $products->where('status', self::STATUS_LOW_STOCK)->count(),
            'out_of_stock_count'  => $products->where('status', self::STATUS_OUT_OF_STOCK)->count(),
            'inventory_value_usd' => round($inventoryValue, 2),
            'inventory_value_ves' => round($inventoryValue * $bcvRate, 2),
        ];
    }

    /**
     * Productos que requieren atención: primero los agotados, luego stock bajo.
     */
    private function buildAlerts(Collection $products): Collection
    {
        return $products
            ->whereIn('status', [self::STATUS_OUT_OF_STOCK, self::STATUS_LOW_STOCK])
            ->sortBy([
                fn ($a, $b) => ($a['status'] === self::STATUS_OUT_OF_STOCK ? 0 : 1) <=> ($b['status'] === self::STATUS_OUT_OF_STOCK ? 0 : 1),
                fn ($a, $b) => $a['current_stock'] <=> $b['current_stock'],
            ])
            ->values();
    }

    /**
     * Determina el estado de stock de un producto.
     */
    private function resolveStatus(Product $product): string
    {
        if ($product->current_stock <= 0) {
            return self::STATUS_OUT_OF_STOCK;
        }

        return $product->current_stock <= $product->min_stock
            ? self::STATUS_LOW_STOCK
            : self::STATUS_IN_STOCK;
    }
}
