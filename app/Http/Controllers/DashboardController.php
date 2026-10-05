<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal con métricas, indicadores y tasa BCV.
     */
    public function index(DashboardService $dashboardService): Response
    {
        $bcvRate = (float) Cache::get('bcv_rate', 36.50);

        return Inertia::render('Dashboard', $dashboardService->getDashboardData($bcvRate));
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
