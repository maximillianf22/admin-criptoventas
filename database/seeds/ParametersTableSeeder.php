<?php

use App\Parameter;
use Illuminate\Database\Seeder;

class ParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::create([
            'name' => 'Tipos de identificacion'
        ]);
        Parameter::create([
            'name' => 'Tipos de pago'
        ]);
        Parameter::create([
            'name' => 'Categorias de ingredientes'
        ]);
        Parameter::create([
            'name' => 'Tipos de comercio'
        ]);
        Parameter::create([
            'name' => 'Configuracion de domicilios'
        ]);
        Parameter::create([
            'name' => 'Estado del pedido'
        ]);
        Parameter::create([
            'name' => 'Estado del pago'
        ]);
    }
}
