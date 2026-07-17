<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREACIÓN O ACTUALIZACIÓN DE LOS ROLES (Primero lo primero)
        $adminRole = Role::firstOrCreate(
            ['nombre_rol' => 'Administrador'],
            ['permissions' => json_encode(['ver_inventario', 'editar_inventario', 'ver_reportes', 'procesar_ventas', 'gestionar_usuarios'])]
        );

        $vendedorRole = Role::firstOrCreate(
            ['nombre_rol' => 'Vendedor'],
            ['permissions' => json_encode(['ver_inventario', 'procesar_ventas'])]
        );

        $almaceneroRole = Role::firstOrCreate(
            ['nombre_rol' => 'Almacenero'],
            ['permissions' => json_encode(['ver_inventario', 'editar_inventario', 'gestionar_compras'])]
        );

        // 2. CREACIÓN DE LOS USUARIOS (Ahora sí las variables están declaradas arriba)
        // Administrador Principal
        User::firstOrCreate(
            ['email' => 'admin@corporacionportugal.com'],
            [
                'name' => 'Aaron Diego Palomino',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
            ]
        );

        // Vendedor (Punto de Venta)
        User::firstOrCreate(
            ['email' => 'ventas@corporacionportugal.com'],
            [
                'name' => 'Juan Pérez POS',
                'password' => Hash::make('empleado123'),
                'role_id' => $vendedorRole->id,
            ]
        );

        // Almacenero (Inventario)
        User::firstOrCreate(
            ['email' => 'almacen@corporacionportugal.com'],
            [
                'name' => 'Carlos Almacén',
                'password' => Hash::make('empleado123'),
                'role_id' => $almaceneroRole->id,
            ]
        );
    }
}