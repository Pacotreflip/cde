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

use Ghi\Domain\ReportesActividad\Actividad;
use Ghi\Domain\ReportesActividad\ReporteActividad;
use Ghi\Domain\ReportesActividad\TipoHora;

$factory->define(Ghi\Domain\Core\Usuarios\User::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'is_admin' => $faker->boolean(80),
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(ReporteActividad::class, function ($faker) {
    return [
        'id_almacen'          => 1,
        'fecha'               => $faker->dateTimeThisMonth(),
        'horometro_inicial'   => 0,
        'horometro_final'     => 0,
        'kilometraje_inicial' => 0,
        'kilometraje_final'   => 0,
        'operador'            => 'Uziel Bueno',
        'observaciones'       => $faker->sentence,
        'creado_por'          => 'ubueno',
    ];
});

$factory->define(Actividad::class,function ($faker) {
    return [
        'turno'             => 1,
        'hora_inicial'      => $faker->time(),
        'hora_final'        => $faker->time(),
        'cantidad'          => 1,
        'creado_por'        => 'usuario',
        'observaciones'     => $faker->sentence,
    ];
});

$factory->defineAs(Actividad::class, 'efectivas', function ($faker) use ($factory) {
    $actividad = $factory->raw(Actividad::class);

    return array_merge($actividad, [
        'tipo_hora'         => TipoHora::EFECTIVA,
        'cantidad'          => $faker->randomElement([6, 8, 10]),
        'id_concepto'       => 1,
        'observaciones'     => 'EFECTIVAS',
    ]);
});

$factory->defineAs(Actividad::class, 'rep_mayor', function ($faker) use ($factory) {
    $actividad = $factory->raw(Actividad::class);

    return array_merge($actividad, [
        'tipo_hora'         => TipoHora::REPARACION_MAYOR,
        'turno'             => 2,
        'cantidad'          => $faker->randomElement([2, 3, 5]),
        'observaciones'     => 'REPARACION MAYOR',
    ]);
});

$factory->defineAs(Actividad::class, 'rep_mayor_cargo', function ($faker) use ($factory) {
    $actividad = $factory->raw(Actividad::class);

    return array_merge($actividad, [
        'tipo_hora'         => TipoHora::REPARACION_MAYOR,
        'turno'             => 2,
        'cantidad'          => $faker->randomElement([2, 3, 5]),
        'con_cargo_empresa' => true,
        'observaciones'     => 'REPARACION MAYOR CON CARGO',
    ]);
});

$factory->defineAs(Actividad::class, 'rep_menor', function ($faker) use ($factory) {
    $actividad = $factory->raw(Actividad::class);

    return array_merge($actividad, [
        'tipo_hora'         => TipoHora::REPARACION_MENOR,
        'turno'             => $faker->randomElement([1, 2]),
        'cantidad'          => $faker->randomElement([1, 2, 3]),
        'observaciones'     => 'REPARACION MAYOR',
    ]);
});

$factory->defineAs(Actividad::class, 'mantenimiento', function ($faker) use ($factory) {
    $actividad = $factory->raw(Actividad::class);

    return array_merge($actividad, [
        'tipo_hora'         => TipoHora::MANTENIMIENTO,
        'turno'             => 2,
        'cantidad'          => $faker->randomElement([1, 2, 3,]),
        'observaciones'     => 'MANTENIMIENTO',
    ]);
});

$factory->defineAs(Actividad::class, 'ocio', function ($faker) use ($factory) {
    $actividad = $factory->raw(Actividad::class);

    return array_merge($actividad, [
        'tipo_hora'         => TipoHora::OCIO,
        'turno'             => $faker->randomElement([1, 2]),
        'cantidad'          => $faker->randomElement([1, 2, 5]),
        'observaciones'     => 'MANTENIMIENTO',
    ]);
});

$factory->defineAs(Actividad::class, 'traslado', function ($faker) use ($factory) {
    $actividad = $factory->raw(Actividad::class);

    return array_merge($actividad, [
        'tipo_hora'         => TipoHora::TRASLADO,
        'turno'             => $faker->randomElement([1, 2]),
        'cantidad'          => $faker->randomElement([1, 2, 3]),
        'observaciones'     => 'TRASLADO',
    ]);
});