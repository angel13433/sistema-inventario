<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Electrónica y Tecnología',
                'description' => 'Teléfonos, computadoras, accesorios y equipos electrónicos en general.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Ferretería y Construcción',
                'description' => 'Materiales de construcción, pinturas, tuberías y acabados.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Alimentos y Bebidas',
                'description' => 'Productos alimenticios, granos, aceites y bebidas.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Higiene y Cuidado Personal',
                'description' => 'Artículos de higiene, cosméticos y cuidado corporal.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Papelería y Oficina',
                'description' => 'Insumos de oficina, papelería, útiles escolares y escolares.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Herramientas y Equipos',
                'description' => 'Herramientas manuales, eléctricas y equipos industriales.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Hogar y Electrodomésticos',
                'description' => 'Electrodomésticos, utensilios y artículos para el hogar.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Ropa y Calzado',
                'description' => 'Indumentaria, calzado y accesorios de vestir.',
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
