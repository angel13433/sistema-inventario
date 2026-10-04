<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'Distribuidora Tecnológica Carabobo C.A.',
                'rif'            => 'J-30123456-7',
                'phone'          => '0212-555-1234',
                'email'          => 'ventas@dteccarabobo.com.ve',
                'address'        => 'Av. Bolívar Norte, C.C. Prebo, Local 14, Valencia, Carabobo',
                'contact_person' => 'Ing. Carlos Rodríguez',
                'is_active'      => true,
            ],
            [
                'name'           => 'Materiales El Constructor C.A.',
                'rif'            => 'J-29876543-2',
                'phone'          => '0261-444-5678',
                'email'          => 'pedidos@elconstructor.com.ve',
                'address'        => 'Zona Industrial, Calle 71, Galpón B-12, Maracaibo, Zulia',
                'contact_person' => 'Sr. Ramón Pérez',
                'is_active'      => true,
            ],
            [
                'name'           => 'Distribuidora Alimentos Nacionales C.A.',
                'rif'            => 'J-00123456-7',
                'phone'          => '0212-333-9876',
                'email'          => 'comercial@alinacar.com.ve',
                'address'        => 'Mercado Mayorista, Galpón 45, Caracas, Distrito Capital',
                'contact_person' => 'Sra. Luisa Fernández',
                'is_active'      => true,
            ],
            [
                'name'           => 'Global Trade Venezuela C.A.',
                'rif'            => 'J-31234567-8',
                'phone'          => '0212-666-1111',
                'email'          => 'info@globaltradevenezuela.com',
                'address'        => 'Av. Francisco de Miranda, Edificio Miranda, Piso 3, Chacao, Caracas',
                'contact_person' => 'Lic. Andrés Morales',
                'is_active'      => true,
            ],
            [
                'name'           => 'Proveedor Industrial Venezolano C.A.',
                'rif'            => 'J-29345678-4',
                'phone'          => '0241-777-2222',
                'email'          => 'industrial@pivca.com.ve',
                'address'        => 'Zona Industrial Los Colorados, Galpón 8, Valencia, Carabobo',
                'contact_person' => 'Ing. Miguel Torres',
                'is_active'      => true,
            ],
            [
                'name'           => 'Distribuidora El Hogar del Sur C.A.',
                'rif'            => 'J-30987654-1',
                'phone'          => '0286-888-3333',
                'email'          => 'ventas@elhogardelsur.com.ve',
                'address'        => 'CC Ciudad Comercial, Local 22-B, Puerto Ordaz, Bolívar',
                'contact_person' => 'Sra. Patricia Blanco',
                'is_active'      => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['rif' => $supplier['rif']], $supplier);
        }
    }
}
