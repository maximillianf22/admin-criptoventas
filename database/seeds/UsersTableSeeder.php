<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'document' => '1123456',
            'document_type_vp' => 1,
            'name' => 'Admin',
            'last_name' => 'Administrador',
            'email' => 'admin@admin.com',
            'cellphone' => '30123',
            'password' => bcrypt('123456'),
            'rol_id' => 1,
            'user_state' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
