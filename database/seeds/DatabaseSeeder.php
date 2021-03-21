<?php

use App\ParameterValue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ParametersTableSeeder::class,
            ParameterValueTableSeeder::class,
            RolesTableSeeder::class,
            ModulesTableSeeder::class,
            PermitsTableSeeder::class,
            UsersTableSeeder::class
        ]);
    }
}
