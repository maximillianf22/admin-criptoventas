<?php

use App\Permit;
use Illuminate\Database\Seeder;

class PermitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permit::create([
            'module_id' => 1,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 2,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 3,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 4,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 5,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 6,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 7,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 8,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 9,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 10,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 11,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 12,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 13,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 14,
            'rol_id' => 1,
        ]);
        Permit::create([
            'module_id' => 15,
            'rol_id' => 1,
        ]);
    }
}
