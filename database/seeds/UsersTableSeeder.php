<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Entities\User::class)->create([
          'name' => 'Administrador',
          'email' => 'admin@lars.com',
          'password' => 'Soporte2011',
          'remember_token' => str_random(10),
          'role' => 'superadmin',
        ]);
        // factory(App\User::class, 100)->create();
    }
}
