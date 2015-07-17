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

$factory->define(Ghi\Domain\Core\Usuarios\User::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'is_admin' => $faker->boolean(80),
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Ghi\Domain\Almacenes\Propiedad::class, function ($faker) {
    return [
        'descripcion' => $faker->word,
    ];
});

$factory->define(Ghi\Domain\Almacenes\Clasificacion::class, function ($faker) {
    return [
        'descripcion' => $faker->word,
    ];
});

$factory->define(Ghi\Domain\ReportesActividad\TipoHora::class, function ($faker) {
    return [
        'descripcion' => $faker->word,
    ];
});
