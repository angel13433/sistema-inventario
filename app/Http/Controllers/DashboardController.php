<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal con métricas, indicadores y tasa BCV.
     */
    public function index(): Response
    {
        $bcvRate = (float) Cache::get('bcv_rate', 36.50);

        // Métricas del inventario
        $totalProducts   = Product::active()->count();
        $lowStockCount   = Product::active()->lowStock()->where('current_stock', '>', 0)->count();
        $outOfStockCount = Product::active()->where('current_stock', '<=', 0)->count();
        $inventoryValue  = (float) Product::active()
            ->select(DB::raw('COALESCE(SUM(price_usd * current_stock), 0) as total'))
            ->value('total');

        // Productos en alerta (stock bajo o agotados)
        $lowStockProducts = Product::with('category:id,name')
            ->active()
            ->lowStock()
            ->orderBy('current_stock')
            ->limit(10)
            ->get(['id', 'name', 'sku', 'current_stock', 'min_stock', 'category_id', 'price_usd']);

        // Últimos movimientos de inventario
        $recentMovements = InventoryMovement::with([
                'product:id,name,sku',
                'user:id,name',
            ])
            ->latest()
            ->limit(6)
            ->get();

        return Inertia::render('Dashboard', [
            'metrics' => [
                'total_products'      => $totalProducts,
                'low_stock_count'     => $lowStockCount,
                'out_of_stock_count'  => $outOfStockCount,
                'inventory_value_usd' => number_format($inventoryValue, 2),
                'inventory_value_ves' => number_format($inventoryValue * $bcvRate, 2),
            ],
            'bcvRate'          => $bcvRate,
            'lowStockProducts' => $lowStockProducts,
            'recentMovements'  => $recentMovements,
        ]);
    }

    /**
     * Actualiza la tasa del Banco Central de Venezuela (BCV) en caché.
     */
    public function updateBcvRate(Request $request): RedirectResponse
    {
        $request->validate([
            'rate' => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
        ]);

        $rate = (float) $request->rate;
        Cache::put('bcv_rate', $rate, now()->addDays(30));

        return back()->with('success', 'Tasa BCV actualizada: Bs. ' . number_format($rate, 2) . ' / USD');
    }
}
