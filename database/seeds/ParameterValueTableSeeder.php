<?php

use App\ParameterValue;
use Illuminate\Database\Seeder;

class ParameterValueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParameterValue::create([
            'parameter_id' => 1,
            'name' => 'Cedula',
            'extra' => 'CC'
        ]);
        ParameterValue::create([
            'parameter_id' => 1,
            'name' => 'Nit',
            'extra' => 'NIT'
        ]);
        ParameterValue::create([
            'parameter_id' => 1,
            'name' => 'Tarjeta de pasaporte',
            'extra' => 'TP'
        ]);
        ParameterValue::create([
            'parameter_id' => 2,
            'name' => 'Contraentrega',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 2,
            'name' => 'PayU',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 3,
            'name' => 'Adicionales',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 3,
            'name' => 'Obligatorio',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 3,
            'name' => 'Regular',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 4,
            'name' => 'Restaurante',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 4,
            'name' => 'Supermercado',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 5,
            'name' => 'No usa',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 5,
            'name' => 'Domicilio fijo',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 5,
            'name' => 'Domiclio variable',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 6,
            'name' => 'En proceso de solicitud',
            'extra' => ''
        ]);
        ParameterValue::create([
            'parameter_id' => 6,
            'name' => 'Solicitado',
            'extra' => 1
        ]);
        ParameterValue::create([
            'parameter_id' => 6,
            'name' => 'En proceso de seleccion',
            'extra' => 2
        ]);
        ParameterValue::create([
            'parameter_id' => 6,
            'name' => 'En ruta',
            'extra' => 3
        ]);
        ParameterValue::create([
            'parameter_id' => 6,
            'name' => 'Entregado',
            'extra' => 4
        ]);
        ParameterValue::create([
            'parameter_id' => 7,
            'name' => 'Pendiente por pago'
        ]);
        ParameterValue::create([
            'parameter_id' => 7,
            'name' => 'Aceptado PayU'
        ]);
        ParameterValue::create([
            'parameter_id' => 7,
            'name' => 'Rechazado PayU'
        ]);
    }
}
