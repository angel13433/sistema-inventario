<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate respetando el orden de dependencias (PostgreSQL CASCADE)
        DB::statement('TRUNCATE TABLE inventory_movements RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE products RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE categories RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE suppliers RESTART IDENTITY CASCADE');

        // Crear usuario administrador de demostración
        User::firstOrCreate(
            ['email' => 'admin@inventario.ve'],
            [
                'name'              => 'Angel Machado',
                'email'             => 'admin@inventario.ve',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Sembrar catálogos en el orden correcto de dependencias
        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
        ]);

        $this->command->info('✅ Base de datos sembrada exitosamente.');
        $this->command->info('   👤 Usuario: admin@inventario.ve  |  Contraseña: password');
    }
}
