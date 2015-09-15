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

$factory->define(\Ghi\Core\Models\Obra::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->name,
        'descripcion' => $faker->sentence,
        'estadoObra' => 'En Ejecucion',
        'constructora' => $faker->company,
        'cliente' => $faker->company,
        'facturar' => $faker->company,
        'responsable' => $faker->name,
        'rfc' => 'LP561029CR1',
        'direccion' => $faker->address,
        'ciudad' => $faker->city,
        'codigoPostal' => $faker->postcode,
        'estado' => $faker->state,
        'moneda' => 1,
        'iva' => 16,
        'fechaInicial' => $faker->dateTimeThisYear,
        'fechaFinal' => $faker->dateTimeThisYear,
    ];
});

$factory->define(Ghi\Core\Models\UsuarioCadeco::class, function (Faker\Generator $faker) {
    $usuario = factory(Ghi\Core\Models\User::class)->create();

    return [
        'usuario' => $usuario->usuario,
        'nombre' => $usuario->nombre,
        'id_obra' => null,
    ];
});

$factory->define(Ghi\Core\Models\User::class, function (Faker\Generator $faker) {
    return [
        'usuario' => $faker->username,
        'correo' => $faker->email,
        'clave'  => 'secret',
        'nombre' => $faker->name,
        'idubicacion' => 1,
        'idempresa' => 1,
        'iddepartamento' => 1,
        'idtitulo' => 1,
        'idgenero' => 1,
        'idpuesto' => 1,
        'remember_token' => str_random(10),
    ];
});

$factory->define(Ghi\Equipamiento\Areas\Tipo::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->toUpper($faker->streetName),
        'descripcion' => $faker->paragraph,
    ];
});

$factory->define(Ghi\Equipamiento\Areas\Area::class, function (Faker\Generator $faker) {
    return [
        'nombre' => implode(' ', $faker->words),
        'clave'  => $faker->citySuffix,
        'descripcion' => $faker->paragraph,
    ];
});

$factory->define(Ghi\Equipamiento\Articulos\Clasificador::class, function(Faker\Generator $faker) {
    return [
        'nombre' => $faker->sentence
    ];
});

$factory->define(Ghi\Equipamiento\Articulos\Unidad::class, function (Faker\Generator $faker) {
    return [
        'unidad' => $faker->currencyCode,
        'descripcion' => $faker->word,
        'tipo_unidad' => Ghi\Equipamiento\Articulos\Unidad::TIPO_GENERICA,
    ];
});

$factory->define(Ghi\Equipamiento\Articulos\Familia::class, function (Faker\Generator $faker) {
    return [
        'descripcion'   => $faker->sentence,
        'tipo_material' => Ghi\Equipamiento\Articulos\TipoMaterial::TIPO_MATERIALES,
        'nivel' => '001.',
    ];
});

$factory->define(Ghi\Equipamiento\Articulos\Material::class, function (Faker\Generator $faker) {
    return [
        'descripcion'       => $faker->sentence,
        'descripcion_larga' => $faker->paragraph,
        'codigo_externo'    => $faker->domainWord,
        'numero_parte'      => $faker->domainWord,
        'unidad'            => null,
        'unidad_compra'     => null,
        'unidad_capacidad'  => null,
        'equivalencia'      => 1,
        'marca'             => 0,
        'nivel'             => null,
        'tipo_material'     => Ghi\Equipamiento\Articulos\TipoMaterial::TIPO_MATERIALES,
    ];
});