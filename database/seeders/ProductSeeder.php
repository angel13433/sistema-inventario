<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Recuperar IDs por nombre/RIF para asignar relaciones
        $elec   = Category::where('name', 'Electrónica y Tecnología')->value('id');
        $ferr   = Category::where('name', 'Ferretería y Construcción')->value('id');
        $alim   = Category::where('name', 'Alimentos y Bebidas')->value('id');
        $higi   = Category::where('name', 'Higiene y Cuidado Personal')->value('id');
        $pape   = Category::where('name', 'Papelería y Oficina')->value('id');
        $herr   = Category::where('name', 'Herramientas y Equipos')->value('id');

        $supTech      = Supplier::where('rif', 'J-30123456-7')->value('id');
        $supMateri    = Supplier::where('rif', 'J-29876543-2')->value('id');
        $supAlim      = Supplier::where('rif', 'J-00123456-7')->value('id');
        $supGlobal    = Supplier::where('rif', 'J-31234567-8')->value('id');
        $supIndustri  = Supplier::where('rif', 'J-29345678-4')->value('id');

        $products = [
            // ── Electrónica ──────────────────────────────────────────
            [
                'barcode'       => '7898902335346',
                'sku'           => 'ELEC-001',
                'name'          => 'Teléfono Samsung Galaxy A15 128GB',
                'description'   => 'Smartphone Samsung Galaxy A15, 128GB almacenamiento, 6GB RAM, pantalla 6.5" SuperAMOLED, batería 5000 mAh.',
                'price_usd'     => 175.00,
                'min_stock'     => 3,
                'current_stock' => 12,
                'category_id'   => $elec,
                'supplier_id'   => $supTech,
            ],
            [
                'barcode'       => '0195908855475',
                'sku'           => 'ELEC-002',
                'name'          => 'Laptop HP 15 Core i5 8GB 512SSD',
                'description'   => 'Computadora portátil HP 15, Intel Core i5 12va generación, 8GB DDR4, 512GB SSD, pantalla FHD 15.6".',
                'price_usd'     => 450.00,
                'min_stock'     => 2,
                'current_stock' => 5,
                'category_id'   => $elec,
                'supplier_id'   => $supGlobal,
            ],
            [
                'barcode'       => '7891234567890',
                'sku'           => 'ELEC-003',
                'name'          => 'Cable HDMI 4K 1.8m',
                'description'   => 'Cable HDMI 2.0 de alta velocidad, compatible con 4K/60Hz, longitud 1.8 metros.',
                'price_usd'     => 4.50,
                'min_stock'     => 10,
                'current_stock' => 48,
                'category_id'   => $elec,
                'supplier_id'   => $supTech,
            ],
            [
                'barcode'       => '5099206064682',
                'sku'           => 'ELEC-004',
                'name'          => 'Mouse Inalámbrico Logitech M305',
                'description'   => 'Mouse inalámbrico Logitech M305, receptor nano USB, hasta 12 meses de batería.',
                'price_usd'     => 18.00,
                'min_stock'     => 5,
                'current_stock' => 22,
                'category_id'   => $elec,
                'supplier_id'   => $supTech,
            ],
            [
                'barcode'       => '6920677213218',
                'sku'           => 'ELEC-005',
                'name'          => 'Memoria USB Kingston 64GB USB 3.0',
                'description'   => 'Pendrive Kingston DataTraveler Exodia 64GB, USB 3.0, velocidad lectura 200MB/s.',
                'price_usd'     => 8.50,
                'min_stock'     => 8,
                'current_stock' => 6,   // stock bajo: 6 <= 8
                'category_id'   => $elec,
                'supplier_id'   => $supGlobal,
            ],

            // ── Ferretería ───────────────────────────────────────────
            [
                'barcode'       => '7702006122137',
                'sku'           => 'FERR-001',
                'name'          => 'Cemento Portland Tipo GU 42.5kg',
                'description'   => 'Cemento Portland tipo GU, saco de 42.5 kg, ideal para construcción residencial e industrial.',
                'price_usd'     => 8.00,
                'min_stock'     => 20,
                'current_stock' => 55,
                'category_id'   => $ferr,
                'supplier_id'   => $supMateri,
            ],
            [
                'barcode'       => '7702421000187',
                'sku'           => 'FERR-002',
                'name'          => 'Pintura Látex Interior Blanco 4L',
                'description'   => 'Pintura látex de alta cobertura para interiores, color blanco, rendimiento 12 m²/L.',
                'price_usd'     => 12.50,
                'min_stock'     => 10,
                'current_stock' => 28,
                'category_id'   => $ferr,
                'supplier_id'   => $supMateri,
            ],
            [
                'barcode'       => '7590000100048',
                'sku'           => 'FERR-003',
                'name'          => 'Tubería PVC Sanitario 4" x 3m',
                'description'   => 'Tubería PVC color gris para instalaciones sanitarias, diámetro 4 pulgadas, longitud 3 metros.',
                'price_usd'     => 6.00,
                'min_stock'     => 15,
                'current_stock' => 4,   // stock bajo: 4 <= 15
                'category_id'   => $ferr,
                'supplier_id'   => $supMateri,
            ],
            [
                'barcode'       => null,
                'sku'           => 'FERR-004',
                'name'          => 'Varilla de Hierro 3/8" x 6m',
                'description'   => 'Varilla corrugada de hierro para refuerzo estructural, diámetro 3/8 pulgadas, longitud 6 metros.',
                'price_usd'     => 9.50,
                'min_stock'     => 20,
                'current_stock' => 0,   // agotado
                'category_id'   => $ferr,
                'supplier_id'   => $supMateri,
            ],

            // ── Alimentos ────────────────────────────────────────────
            [
                'barcode'       => '7591538001111',
                'sku'           => 'ALIM-001',
                'name'          => 'Arroz Cristal 1kg',
                'description'   => 'Arroz blanco de grano largo tipo cristal, bolsa de 1 kilogramo.',
                'price_usd'     => 0.85,
                'min_stock'     => 50,
                'current_stock' => 200,
                'category_id'   => $alim,
                'supplier_id'   => $supAlim,
            ],
            [
                'barcode'       => '7750048000045',
                'sku'           => 'ALIM-002',
                'name'          => 'Aceite Mazeite de Maíz 900ml',
                'description'   => 'Aceite vegetal de maíz refinado, botella de 900 ml. Libre de colesterol.',
                'price_usd'     => 2.50,
                'min_stock'     => 30,
                'current_stock' => 88,
                'category_id'   => $alim,
                'supplier_id'   => $supAlim,
            ],
            [
                'barcode'       => '7591538003115',
                'sku'           => 'ALIM-003',
                'name'          => 'Harina de Maíz P.A.N. 1kg',
                'description'   => 'Harina de maíz precocida P.A.N., bolsa de 1 kilogramo. Sin gluten.',
                'price_usd'     => 1.20,
                'min_stock'     => 50,
                'current_stock' => 14,  // stock bajo: 14 <= 50
                'category_id'   => $alim,
                'supplier_id'   => $supAlim,
            ],
            [
                'barcode'       => '7421600000019',
                'sku'           => 'ALIM-004',
                'name'          => 'Café Madrid Tostado y Molido 500g',
                'description'   => 'Café venezolano tostado y molido, selección de granos arabica, bolsa de 500g.',
                'price_usd'     => 3.80,
                'min_stock'     => 20,
                'current_stock' => 42,
                'category_id'   => $alim,
                'supplier_id'   => $supAlim,
            ],

            // ── Higiene ──────────────────────────────────────────────
            [
                'barcode'       => '7501031308105',
                'sku'           => 'HIGI-001',
                'name'          => 'Shampoo Head & Shoulders 400ml',
                'description'   => 'Shampoo anticaspa Head & Shoulders 2 en 1, botella de 400ml.',
                'price_usd'     => 3.20,
                'min_stock'     => 15,
                'current_stock' => 36,
                'category_id'   => $higi,
                'supplier_id'   => $supGlobal,
            ],
            [
                'barcode'       => '7501006800209',
                'sku'           => 'HIGI-002',
                'name'          => 'Jabón Protex Original 125g x3',
                'description'   => 'Jabón antibacterial Protex Original, pack de 3 unidades de 125g cada una.',
                'price_usd'     => 1.80,
                'min_stock'     => 20,
                'current_stock' => 19,
                'category_id'   => $higi,
                'supplier_id'   => $supGlobal,
            ],
            [
                'barcode'       => '7613034378804',
                'sku'           => 'HIGI-003',
                'name'          => 'Desodorante Rexona Men 150ml',
                'description'   => 'Desodorante spray Rexona Men Xtracool, protección 48h, 150ml.',
                'price_usd'     => 2.50,
                'min_stock'     => 10,
                'current_stock' => 31,
                'category_id'   => $higi,
                'supplier_id'   => $supGlobal,
            ],

            // ── Papelería ────────────────────────────────────────────
            [
                'barcode'       => '7802046108303',
                'sku'           => 'PAPE-001',
                'name'          => 'Resma Papel Bond Carta 500 hojas',
                'description'   => 'Resma de papel bond tamaño carta, 500 hojas, 75g/m², blancura 96%.',
                'price_usd'     => 6.00,
                'min_stock'     => 10,
                'current_stock' => 25,
                'category_id'   => $pape,
                'supplier_id'   => $supGlobal,
            ],
            [
                'barcode'       => '0070330306000',
                'sku'           => 'PAPE-002',
                'name'          => 'Bolígrafos BIC Round Stic x12',
                'description'   => 'Caja de 12 bolígrafos BIC Round Stic, punta media 1mm, color azul.',
                'price_usd'     => 2.80,
                'min_stock'     => 15,
                'current_stock' => 48,
                'category_id'   => $pape,
                'supplier_id'   => $supGlobal,
            ],

            // ── Herramientas ─────────────────────────────────────────
            [
                'barcode'       => '3165140573481',
                'sku'           => 'HERR-001',
                'name'          => 'Taladro Percutor Bosch 600W',
                'description'   => 'Taladro percutor Bosch PSB 600 RE, 600W, velocidad variable, mandril 13mm.',
                'price_usd'     => 95.00,
                'min_stock'     => 2,
                'current_stock' => 4,
                'category_id'   => $herr,
                'supplier_id'   => $supIndustri,
            ],
            [
                'barcode'       => null,
                'sku'           => 'HERR-002',
                'name'          => 'Set Destornilladores Stanley 12 pzas',
                'description'   => 'Juego de 12 destornilladores Stanley, incluye planos y de estrella, mangos ergonómicos.',
                'price_usd'     => 15.00,
                'min_stock'     => 5,
                'current_stock' => 11,
                'category_id'   => $herr,
                'supplier_id'   => $supIndustri,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['sku' => $data['sku']],
                $data
            );
        }
    }
}
