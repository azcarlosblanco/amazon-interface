<?php

use Illuminate\Database\Seeder;

class CredentialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Entities\Credential::class)->create([
            'access_token' => null,
            'refresh_token' => null,
            'user_id' => null,
        ]);
    }
}
