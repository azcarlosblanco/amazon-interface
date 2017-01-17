<?php

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
        factory(App\Entities\Parameter::class)->create([
            'TRM'            => 3000,
            'tax_usa'        => 0.07,
            'iva_co'         => 0.17,
            'costo_envio_kg' => 15500,
            'default_weight' => 1,
            'utilidad'       => 0.10,
            'comision_meli'  => 0.10,
            'comision_linio' => 0.20,
        ]);
    }
}
