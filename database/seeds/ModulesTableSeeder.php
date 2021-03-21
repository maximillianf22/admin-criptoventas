<?php

use App\Module;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::create([
            'name' => 'Dashboard',
            'reference' => 'dashboard',
        ]);
        Module::create([
            'name' => 'Categorias de comercios',
            'reference' => 'commercesCategory',
        ]);
        Module::create([
            'name' => 'Comercios',
            'reference' => 'commerces',
        ]);
        Module::create([
            'name' => 'Categorias de productos',
            'reference' => 'categories',
        ]);
        Module::create([
            'name' => 'Productos',
            'reference' => 'productsCommerce',
        ]);
        Module::create([
            'name' => 'Unidades de venta',
            'reference' => 'units',
        ]);
        Module::create([
            'name' => 'Minimo de compra',
            'reference' => 'minShopping',
        ]);
        Module::create([
            'name' => 'Clientes',
            'reference' => 'customers',
        ]);
        Module::create([
            'name' => 'Distribuidores',
            'reference' => 'distributors',
        ]);
        Module::create([
            'name' => 'Pedidos',
            'reference' => 'orders',
        ]);
        Module::create([
            'name' => 'Sliders',
            'reference' => 'sliders',
        ]);
        Module::create([
            'name' => 'Horas de entrega',
            'reference' => 'shipping',
        ]);
        Module::create([
            'name' => 'Parametros',
            'reference' => 'parameters',
        ]);
        Module::create([
            'name' => 'Usuarios',
            'reference' => 'users',
        ]);
        Module::create([
            'name' => 'Permisos',
            'reference' => 'permits',
        ]);
        Module::create([
            'name' => 'Tips',
            'reference' => 'tips',
        ]);
        Module::create([
            'name' => 'cupones',
            'reference' => 'coupons',
        ]);
    }
}
