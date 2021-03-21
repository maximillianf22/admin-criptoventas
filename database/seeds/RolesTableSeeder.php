<?php

use App\Rol;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'name' => 'Administrador',
            'unique' => 1
        ]);
        Rol::create([
            'name' => 'Comercios',
            'unique' => 1
        ]);
        Rol::create([
            'name' => 'Clientes',
            'unique' => 0
        ]);
        Rol::create([
            'name' => 'Distribuidores',
            'unique' => 0
        ]);
        Rol::create([
            'name' => 'Mayoristas',
            'unique' => 0
        ]);
        Rol::create([
            'name' => 'Sistema',
            'unique' => 1
        ]);
    }
}
