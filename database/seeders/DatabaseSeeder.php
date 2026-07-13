<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'nombre_rol' => 'Administrador', 'permissions' => json_encode(['*'])],
            ['id' => 2, 'nombre_rol' => 'Vendedor', 'permissions' => json_encode(['ver_ventas', 'crear_ventas'])],
            ['id' => 3, 'nombre_rol' => 'Inventario', 'permissions' => json_encode(['ver_stock', 'editar_stock'])],
        ]);

        DB::table('users')->insertOrIgnore([
            'name' => 'Admin Portugal',
            'email' => 'admin@corporacionportugal.com',
            'password' => Hash::make('portugal2026'), 
            'role_id' => 1, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insertOrIgnore([
            'name' => 'Carlos Mendoza Venta',
            'email' => 'carlos.mendoza@corporacionportugal.com',
            'password' => Hash::make('ventas2026'), 
            'role_id' => 2, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}