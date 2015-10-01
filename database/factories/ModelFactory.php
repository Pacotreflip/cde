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
        'nombre'        => $faker->name,
        'descripcion'   => $faker->sentence,
        'tipo_obra'     => 1,
        'constructora'  => $faker->company,
        'cliente'       => $faker->company,
        'descripcion'   => $faker->text,
        'direccion'     => $faker->address,
        'ciudad'        => $faker->city,
        'estado'        => $faker->state,
        'codigo_postal' => $faker->randomNumber($nbDigits=5),
        'fecha_inicial' => $faker->dateTimeThisYear,
        'fecha_final'   => $faker->dateTimeThisYear,
        'iva'           => 16,
        'id_moneda'     => factory(Ghi\Core\Models\Moneda::class)->create()->id_moneda,
        'facturar'      => $faker->company,
        'responsable'   => $faker->name,
        'rfc'           => $faker->word,
    ];
});

$factory->define(Ghi\Core\Models\Moneda::class, function (Faker\Generator $faker) {
    return [
        'nombre'      => $faker->name,
        'tipo'        => 0,
        'abreviatura' => $faker->name,
    ];
});

$factory->define(Ghi\Core\Models\UsuarioCadeco::class, function (Faker\Generator $faker) {
    return [
        'usuario' => $faker->username,
        'nombre'  => $faker->name,
        'id_obra' => null,
    ];
});

$factory->define(Ghi\Core\Models\User::class, function (Faker\Generator $faker) {
    $usuario_cadeco = factory(Ghi\Core\Models\UsuarioCadeco::class)->create();

    return [
        'usuario'        => $usuario_cadeco->usuario,
        'correo'         => $faker->email,
        'clave'          => 'secret',
        'nombre'         => $usuario_cadeco->nombre,
        'idubicacion'    => 1,
        'idempresa'      => 1,
        'iddepartamento' => 1,
        'idtitulo'       => 1,
        'idgenero'       => 1,
        'idpuesto'       => 1,
        'remember_token' => str_random(10),
    ];
});

$factory->define(Ghi\Equipamiento\Areas\Tipo::class, function (Faker\Generator $faker) {
    return [
        'nombre'      => $faker->toUpper($faker->streetName),
        'descripcion' => $faker->paragraph,
    ];
});

$factory->define(Ghi\Equipamiento\Areas\Area::class, function (Faker\Generator $faker) {
    return [
        'nombre'      => implode(' ', $faker->words),
        'clave'       => $faker->citySuffix,
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
        'unidad'      => str_random(),
        'descripcion' => $faker->word,
        'tipo_unidad' => Ghi\Equipamiento\Articulos\Unidad::TIPO_GENERICA,
    ];
});

$factory->define(Ghi\Equipamiento\Articulos\Familia::class, function (Faker\Generator $faker) {
    return [
        'descripcion'   => $faker->sentence,
        'tipo_material' => Ghi\Equipamiento\Articulos\TipoMaterial::TIPO_MATERIALES,
        'nivel'         => '001.',
    ];
});

$factory->define(Ghi\Equipamiento\Articulos\Material::class, function (Faker\Generator $faker) {
    return [
        'descripcion'       => $faker->sentence,
        'descripcion_larga' => $faker->paragraph,
        'codigo_externo'    => $faker->domainWord,
        'numero_parte'      => $faker->domainWord,
        'unidad'            => factory(Ghi\Equipamiento\Articulos\Unidad::class)->create()->unidad,
        'unidad_compra'     => null,
        'unidad_capacidad'  => null,
        'equivalencia'      => 1,
        'marca'             => 0,
        'nivel'             => str_random(30),
        'tipo_material'     => Ghi\Equipamiento\Articulos\TipoMaterial::TIPO_MATERIALES,
    ];
});

$factory->define(Ghi\Equipamiento\Proveedores\Proveedor::class, function (Faker\Generator $faker) {
    return [
        'tipo_empresa'    => Ghi\Equipamiento\Proveedores\Tipo::PROVEEDOR_MATERIALES,
        'razon_social'    => $faker->company,
        'rfc'             => $faker->domainWord,
        'dias_credito'    => 0,
        'cuenta_contable' => $faker->companySuffix,
        'tipo_cliente'    => 0,
    ];
});

$factory->define(Ghi\Equipamiento\Transacciones\Transaccion::class, function (Faker\Generator $faker) {
    $obra = Ghi\Core\Models\Obra::find(1) ?: factory(Ghi\Core\Models\Obra::class)->create();
    $moneda = Ghi\Core\Models\Moneda::find(1) ?: factory(Ghi\Core\Models\Moneda::class)->create();

    return [
        'id_obra'          => $obra->id_obra,
        'tipo_transaccion' => null,
        'numero_folio'     => $faker->randomNumber($nbDigits=6),
        'fecha'            => $faker->dateTimeThisYear,
        'id_empresa'       => null,
        'id_sucursal'      => null,
        'id_moneda'        => $moneda->id_moneda,
        'opciones'         => 1,
        'monto'            => $faker->randomFloat,
        'saldo'            => $faker->randomFloat,
        'impuesto'         => $faker->randomFloat,
        'comentario'       => $faker->sentence,
        'observaciones'    => $faker->text,
    ];
});

$factory->defineAs(Ghi\Equipamiento\Transacciones\Transaccion::class, 'orden_compra', function (Faker\Generator $faker) use ($factory) {
    $transaccion = $factory->raw(Ghi\Equipamiento\Transacciones\Transaccion::class);

    return array_merge($transaccion, [
        'porcentaje_anticipo_pactado' => $faker->randomNumber($nbDigits=3),
        'tipo_transaccion'            => Ghi\Equipamiento\Transacciones\Tipo::ORDEN_COMPRA,
        'opciones'                    => 1,
        'id_empresa'                  => factory(Ghi\Equipamiento\Proveedores\Proveedor::class)->create()->id_empresa,
    ]);
});

$factory->define(Ghi\Equipamiento\Recepciones\Recepcion::class, function (Faker\Generator $faker) {
    $orden_compra = factory(Ghi\Equipamiento\Transacciones\Transaccion::class, 'orden_compra')->create();

    return [
        'id_obra'                => $orden_compra->id_obra,
        'numero_folio'           => $faker->randomNumber($nbDigits=6),
        'id_empresa'             => $orden_compra->id_empresa,
        'id_orden_compra'        => $orden_compra->id_transaccion,
        'id_area_almacenamiento' => factory(Ghi\Equipamiento\Areas\Area::class)->create(['id_obra' => $orden_compra->id_obra])->id,
        'fecha_recepcion'        => $faker->dateTimeThisYear,
        'referencia_documento'   => $faker->sentence,
        'orden_embarque'         => $faker->sentence,
        'numero_pedido'          => $faker->sentence,
        'persona_recibe'         => $faker->name,
        'observaciones'          => $faker->text,
    ];
});
