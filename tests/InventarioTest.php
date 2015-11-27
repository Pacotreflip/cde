<?php

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Inventarios\Inventario;
use Ghi\Equipamiento\Recepciones\ItemRecepcion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ghi\Equipamiento\Inventarios\Exceptions\SinExistenciaSuficienteException;

class InventarioTest extends \TestCase
{
    use \CadecoDatabaseTransactions;

    /** @test */
    public function test_un_inventario_puede_ser_creado()
    {
        $obra     = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area     = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = $material->nuevoInventarioEnArea($area);
        $inventario->save();

        $this->assertInstanceOf(Inventario::class, $inventario);
        $this->assertEquals(0, $inventario->cantidad);
        $this->assertCount(0, $inventario->movimientos);
    }

    /** @test */
    public function test_crea_inventario_con_movimiento_inicial()
    {
        $obra     = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area     = factory(Area::class)->create(['id_obra' => $obra->id_obra]);
        $item     = factory(ItemRecepcion::class)->create([
            'id_recepcion' => null,
            'id_item' => null,
            'id_material' => $material->id_material,
            'unidad' => $material->unidad,
            'id_area_almacenamiento' => $area->id,
        ]);

        $inventario = $material->creaInventarioEnArea($area, 1, $item);
        $this->assertEquals(1, $inventario->cantidad);
        $this->assertCount(1, $inventario->movimientos);
    }

    /** @test */
    public function test_incrementa_existencia()
    {
        $obra         = factory(Obra::class)->create();
        $material     = factory(Material::class)->create();
        $area_origen  = factory(Area::class)->create(['id_obra' => $obra->id_obra]);
        $area_destino = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = factory(Inventario::class)->create([
            'id_obra'     => $obra->id_obra,
            'id_material' => $material->id_material,
            'id_area'     => $area_origen->id,
            'cantidad'    => 100
        ]);

        $item = factory(ItemTransaccion::class, 'item-recepcion')->create([
            'id_area_origen'  => $area_origen->id,
            'id_area_destino' => $area_destino->id,
            'id_material'     => $material->id_material,
        ]);

        $inventario->incrementaExistencia(10, $item);
        $this->assertEquals(110, $inventario->cantidad);
    }

    /** @test */
    public function test_decrementa_existencia()
    {
        $material     = factory(Material::class)->create();
        $area_origen  = factory(Area::class)->create();
        $area_destino = factory(Area::class)->create(['id_obra' => $area_origen->id_obra]);

        $inventario = factory(Inventario::class)->create([
            'id_obra'     => $area_origen->id_obra,
            'id_material' => $material->id_material,
            'id_area'     => $area_origen->id,
            'cantidad'    => 100
        ]);

        $item = factory(ItemTransaccion::class, 'item-asignacion')->create([
            'id_area_origen'  => $area_origen->id,
            'id_area_destino' => $area_destino->id,
            'id_material'     => $material->id_material,
        ]);

        $inventario->decrementaExistencia(10, $item);
        $this->assertEquals(90, $inventario->cantidad);
    }

    public function test_envia_excepcion_cuando_la_existencia_no_es_suficiente()
    {
        $this->setExpectedException(SinExistenciaSuficienteException::class);

        $material     = factory(Material::class)->create();
        $area_origen  = factory(Area::class)->create();
        $area_destino = factory(Area::class)->create(['id_obra' => $area_origen->id_obra]);

        $inventario = factory(Inventario::class)->create([
            'id_obra'     => $area_origen->id_obra,
            'id_material' => $material->id_material,
            'id_area'     => $area_origen->id,
            'cantidad'    => 50
        ]);

        $item = factory(ItemTransaccion::class, 'item-asignacion')->create([
            'id_area_origen'  => $area_origen->id,
            'id_area_destino' => $area_destino->id,
            'id_material'     => $material->id_material,
            'cantidad'        => 130,
        ]);

        $inventario->decrementaExistencia(130, $item);

        $this->assertEquals(50, $inventario->cantidad);
        $this->assertCount(0, $inventario->movimientos);
    }

    public function test_transferencia_entre_areas()
    {
        $material     = factory(Material::class)->create();
        $area_origen  = factory(Area::class)->create();
        $area_destino = factory(Area::class)->create(['id_obra' => $area_origen->id_obra]);

        // inventario origen con existencia de 10
        $inventario_origen = factory(Inventario::class)->create([
            'id_obra'     => $area_origen->id_obra,
            'id_material' => $material->id_material,
            'id_area'     => $area_origen->id,
            'cantidad'    => 10
        ]);

        // inventario destino con existencia de 0
        $inventario_destino = factory(Inventario::class)->create([
            'id_obra'     => $area_origen->id_obra,
            'id_material' => $material->id_material,
            'id_area'     => $area_destino->id,
            'cantidad'    => 0
        ]);

        // item de una transferencia que sera soporte del movimiento
        $item = factory(ItemTransaccion::class, 'item-transferencia')->create([
            'id_area_origen'  => $area_origen->id,
            'id_area_destino' => $area_destino->id,
            'id_material'     => $material->id_material,
            'cantidad'        => 5,
        ]);

        $inventario_origen->transferirA($item->cantidad, $inventario_destino, $item);

        $this->assertEquals(5, $inventario_origen->cantidad, 'La existencia en origen no es 5');
        $this->assertCount(1, $inventario_origen->movimientos, 'El numero de movimientos en origen debe ser 1');

        $this->assertEquals(5, $inventario_destino->cantidad, 'La existencia en destino debe ser 5');
        $this->assertCount(1, $inventario_destino->movimientos, 'El numero de movimientos en destino debe ser 1');
    }
}
