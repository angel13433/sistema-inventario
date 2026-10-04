<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Lista todos los productos con filtros de búsqueda y paginación.
     */
    public function index(Request $request): Response
    {
        $products = Product::with(['category', 'supplier'])
            ->when($request->search, fn ($q, $search) =>
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('sku', 'ilike', "%{$search}%")
                  ->orWhere('barcode', 'ilike', "%{$search}%")
            )
            ->when($request->category_id, fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->low_stock, fn ($q) => $q->lowStock())
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products'   => $products,
            'categories' => Category::active()->orderBy('name')->get(['id', 'name']),
            'filters'    => $request->only(['search', 'category_id', 'low_stock']),
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create(): Response
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::active()->orderBy('name')->get(['id', 'name']),
            'suppliers'  => Supplier::active()->orderBy('name')->get(['id', 'name', 'rif']),
        ]);
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Muestra el detalle de un producto con su historial de movimientos.
     */
    public function show(Product $product): Response
    {
        $product->load(['category', 'supplier']);

        $movements = $product->inventoryMovements()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return Inertia::render('Products/Show', [
            'product'   => $product,
            'movements' => $movements,
        ]);
    }

    /**
     * Muestra el formulario para editar un producto existente.
     */
    public function edit(Product $product): Response
    {
        return Inertia::render('Products/Edit', [
            'product'    => $product->load(['category', 'supplier']),
            'categories' => Category::active()->orderBy('name')->get(['id', 'name']),
            'suppliers'  => Supplier::active()->orderBy('name')->get(['id', 'name', 'rif']),
        ]);
    }

    /**
     * Actualiza un producto existente en la base de datos.
     * Nota: el stock se gestiona exclusivamente a través de InventoryMovementController.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Elimina (soft delete) un producto de la base de datos.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
