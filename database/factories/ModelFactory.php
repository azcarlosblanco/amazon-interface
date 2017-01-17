<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Entities\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'role' => 'admin',
    ];
});

$factory->define(App\Entities\Parameter::class, function (Faker\Generator $faker) {

    return [
        'TRM'            => null,
        'tax_usa'        => null,
        'iva_co'         => null,
        'costo_envio_kg' => null,
        'default_weight' => null,
        'utilidad'       => null,
        'comision_meli'  => null,
        'comision_linio' => null,
    ];
});

$factory->define(App\Entities\Credential::class, function (Faker\Generator $faker) {
    return [
        'access_token' => null,
        'refresh_token' => null,
        'user_id' => null,
    ];
});
