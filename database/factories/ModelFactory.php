<?php

use Ghi\Core\Models\Obra;
use Ghi\Core\Models\User;
use Ghi\Core\Models\Moneda;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Core\Models\UsuarioCadeco;
use Ghi\Equipamiento\Articulos\Unidad;
use Ghi\Equipamiento\Articulos\Familia;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Transacciones\Entrega;
use Ghi\Equipamiento\Areas\Tipo as TipoArea;
use Ghi\Equipamiento\Articulos\Clasificador;
use Ghi\Equipamiento\Articulos\TipoMaterial;
use Ghi\Equipamiento\Inventarios\Inventario;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Transacciones\ItemTransaccion;
use Ghi\Equipamiento\Proveedores\Tipo as TipoProveedor;
use Ghi\Equipamiento\Transacciones\Tipo as TipoTransaccion;

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

$factory->define(Obra::class, function (Faker\Generator $faker) {
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
        'id_moneda'     => factory(Moneda::class)->create()->id_moneda,
        'facturar'      => $faker->company,
        'responsable'   => $faker->name,
        'rfc'           => $faker->word,
    ];
});

$factory->define(Moneda::class, function (Faker\Generator $faker) {
    return [
        'nombre'      => $faker->name,
        'tipo'        => 0,
        'abreviatura' => $faker->name,
    ];
});

$factory->define(UsuarioCadeco::class, function (Faker\Generator $faker) {
    return [
        'usuario' => $faker->username,
        'nombre'  => $faker->name,
        'id_obra' => null,
    ];
});

$factory->define(User::class, function (Faker\Generator $faker) {
    $usuario_cadeco = factory(UsuarioCadeco::class)->create();

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

$factory->define(TipoArea::class, function (Faker\Generator $faker) {
    return [
        'nombre'      => $faker->toUpper($faker->streetName),
        'descripcion' => $faker->paragraph,
    ];
});

$factory->define(Area::class, function (Faker\Generator $faker) {
    return [
        'nombre'      => implode(' ', $faker->words),
        'clave'       => $faker->citySuffix,
        'descripcion' => $faker->paragraph,
        // 'id_obra'     => factory(Obra::class)->create()->id_obra,
    ];
});

$factory->define(Clasificador::class, function(Faker\Generator $faker) {
    return [
        'nombre' => $faker->sentence
    ];
});

$factory->define(Unidad::class, function (Faker\Generator $faker) {
    return [
        'unidad'      => str_random(),
        'descripcion' => $faker->word,
        'tipo_unidad' => Unidad::TIPO_GENERICA,
    ];
});

$factory->define(Familia::class, function (Faker\Generator $faker) {
    return [
        'descripcion'   => $faker->sentence,
        'tipo_material' => TipoMaterial::TIPO_MATERIALES,
        'nivel'         => '001.',
    ];
});

$factory->define(Material::class, function (Faker\Generator $faker) {
    return [
        'descripcion'       => $faker->sentence,
        'descripcion_larga' => $faker->paragraph,
        'codigo_externo'    => $faker->domainWord,
        'numero_parte'      => $faker->domainWord,
        'unidad'            => factory(Unidad::class)->create()->unidad,
        'unidad_compra'     => null,
        'unidad_capacidad'  => null,
        'equivalencia'      => 1,
        'marca'             => 0,
        'nivel'             => str_random(30),
        'tipo_material'     => TipoMaterial::TIPO_MATERIALES,
    ];
});

$factory->define(Proveedor::class, function (Faker\Generator $faker) {
    return [
        'tipo_empresa'    => TipoProveedor::PROVEEDOR_MATERIALES,
        'razon_social'    => $faker->company,
        'rfc'             => $faker->domainWord,
        'dias_credito'    => 0,
        'cuenta_contable' => $faker->companySuffix,
        'tipo_cliente'    => 0,
    ];
});

$factory->define(Transaccion::class, function (Faker\Generator $faker) {
    $obra = Obra::find(1) ?: factory(Obra::class)->create();
    $moneda = Moneda::find(1) ?: factory(Moneda::class)->create();

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

$factory->defineAs(Transaccion::class, 'orden_compra', function (Faker\Generator $faker) use ($factory) {
    $transaccion = $factory->raw(Transaccion::class);

    return array_merge($transaccion, [
        'porcentaje_anticipo_pactado' => $faker->randomNumber($nbDigits=3),
        'tipo_transaccion'            => TipoTransaccion::ORDEN_COMPRA,
        'opciones'                    => 1,
        'id_empresa'                  => factory(Proveedor::class)->create()->id_empresa,
    ]);
});

$factory->define(Recepcion::class, function (Faker\Generator $faker) {
    $orden_compra = factory(Transaccion::class, 'orden_compra')->create();

    return [
        'id_obra'                => $orden_compra->id_obra,
        'numero_folio'           => $faker->randomNumber($nbDigits=6),
        'id_empresa'             => $orden_compra->id_empresa,
        'id_orden_compra'        => $orden_compra->id_transaccion,
        'id_area_almacenamiento' => factory(Area::class)->create(['id_obra' => $orden_compra->id_obra])->id,
        'fecha_recepcion'        => $faker->dateTimeThisYear,
        'referencia_documento'   => $faker->sentence,
        'orden_embarque'         => $faker->sentence,
        'numero_pedido'          => $faker->sentence,
        'persona_recibe'         => $faker->name,
        'observaciones'          => $faker->text,
    ];
});

$factory->define(ItemTransaccion::class, function (Faker\Generator $faker) {
    return [
        'id_material'      => factory(Material::class)->create()->id_material,
        'cantidad'         => $faker->randomFloat,
        'precio'           => $faker->randomFloat,
        'id_area_origen'   => null,
        'id_area_destino'  => null,
        'id_transaccion'   => null,
        'tipo_transaccion' => null,
    ];
});

$factory->defineAs(ItemTransaccion::class, 'item-recepcion', function (Faker\Generator $faker) use ($factory) {
    $item = $factory->raw(ItemTransaccion::class);

    return array_merge($item, [
        'cantidad'         => $faker->randomFloat,
        'precio'           => $faker->randomFloat,
        'id_area_destino'  => factory(Area::class)->create()->id,
        'id_transaccion'   => factory(Recepcion::class)->create()->id,
        'tipo_transaccion' => Recepcion::class,
    ]);
});

$factory->defineAs(ItemTransaccion::class, 'item-asignacion', function (Faker\Generator $faker) use ($factory) {
    $item = $factory->raw(ItemTransaccion::class);

    return array_merge($item, [
        'cantidad'         => $faker->randomFloat,
        'precio'           => $faker->randomFloat,
        'id_area_origen'   => factory(Area::class)->create()->id,
        'id_area_destino'  => factory(Area::class)->create()->id,
        'id_transaccion'   => factory(Recepcion::class)->create()->id,
        'tipo_transaccion' => Recepcion::class,
    ]);
});

$factory->defineAs(ItemTransaccion::class, 'item-transferencia', function (Faker\Generator $faker) use ($factory) {
    $item = $factory->raw(ItemTransaccion::class);

    return array_merge($item, [
        'cantidad'         => $faker->randomFloat,
        'precio'           => $faker->randomFloat,
        'id_area_origen'   => factory(Area::class)->create()->id,
        'id_area_destino'  => factory(Area::class)->create()->id,
        'id_transaccion'   => factory(Recepcion::class)->create()->id,
        'tipo_transaccion' => Recepcion::class,
    ]);
});

$factory->define(Inventario::class, function (Faker\Generator $faker) {
    return [
        'id_obra'     => factory(Obra::class)->create()->id_obra,
        'id_area'     => factory(Area::class)->create()->id,
        'id_material' => factory(Material::class)->create()->id_material,
        'cantidad'    => $faker->randomFloat
    ];
});