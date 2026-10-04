<?php

namespace App\Http\Controllers;

use App\Enums\MovementType;
use App\Http\Requests\StoreInventoryMovementRequest;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryMovementController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService)
    {
    }

    /**
     * Lista todos los movimientos de inventario con filtros.
     */
    public function index(Request $request): Response
    {
        $movements = InventoryMovement::with(['product:id,name,sku', 'user:id,name'])
            ->when($request->product_id, fn ($q, $id) => $q->where('product_id', $id))
            ->when($request->type, fn ($q, $type) => $q->where('type', $type))
            ->when($request->date_from, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->date_to, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('InventoryMovements/Index', [
            'movements'     => $movements,
            'products'      => Product::active()->orderBy('name')->get(['id', 'name', 'sku']),
            'movementTypes' => collect(MovementType::cases())->map(fn ($case) => [
                'value' => $case->value,
                'label' => $case->label(),
                'color' => $case->color(),
            ]),
            'filters' => $request->only(['product_id', 'type', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Almacena un nuevo movimiento de inventario.
     * Delega la lógica de stock al InventoryService.
     */
    public function store(StoreInventoryMovementRequest $request): RedirectResponse
    {
        $data    = $request->validated();
        $product = Product::findOrFail($data['product_id']);
        $type    = MovementType::from($data['type']);
        $user    = $request->user();

        match ($type) {
            MovementType::Entry      => $this->inventoryService->registerEntry($product, $data['quantity'], $data['reason'], $user, $data['notes'] ?? null),
            MovementType::Exit       => $this->inventoryService->registerExit($product, $data['quantity'], $data['reason'], $user, $data['notes'] ?? null),
            MovementType::Adjustment => $this->inventoryService->adjustStock($product, $data['quantity'], $data['reason'], $user, $data['notes'] ?? null),
        };

        return redirect()->route('inventory-movements.index')
            ->with('success', 'Movimiento de inventario registrado exitosamente.');
    }
}
