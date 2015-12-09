<?php

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Inventarios\Inventario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ghi\Equipamiento\Inventarios\Exceptions\SinExistenciaSuficienteException;

class InventarioTest extends \TestCase
{
    use \CadecoDatabaseTransactions;


    public function test_un_inventario_no_puede_ser_creado_dos_veces_en_la_misma_area_con_el_mismo_material()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario_uno = Inventario::creaInventario($area, $material, 100);
        $inventario_dos = Inventario::creaInventario($area, $material, 50);

        $this->assertEquals($inventario_uno->id, $inventario_dos->id);
    }

    public function test_un_inventario_con_existencia_no_puede_ser_eliminado()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material, 100);

        $inventario->delete();

        $this->assertTrue(Inventario::where('id', $inventario->id)->exists());
    }

    public function test_un_inventario_puede_ser_eliminado_cuando_no_tiene_existencia_y_tenga_solo_un_movimiento()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material, 0);

        $inventario->delete();

        $this->assertFalse(Inventario::where('id', $inventario->id)->exists());
    }

    public function test_inventario_no_puede_transferir_existencia_a_si_mismo()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material, 100);

        $inventario->transferirA($inventario, 50);

        $this->assertEquals(100, $inventario->cantidad_existencia);
        $this->assertCount(1, $inventario->movimientos);
    }

    /** @test */
    public function test_no_pueda_crear_un_inventario_con_numero_negativo()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $this->setExpectedException(\Exception::class);

        $inventario = Inventario::creaInventario($area, $material, -1);

        $this->assertNotInstanceOf(Inventario::class, $Inventario);
    }

    /** @test */
    public function test_un_inventario_puede_ser_creado()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material);

        $this->assertInstanceOf(Inventario::class, $inventario);
        $this->assertEquals(0, $inventario->cantidad_existencia);
        $this->assertCount(1, $inventario->movimientos, 'El inventario debe tener un movimiento');
    }

    /** @test */
    public function test_un_inventario_puede_ser_creado_con_cantidad_inicial()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material, 10);

        $this->assertInstanceOf(Inventario::class, $inventario);
        $this->assertEquals(10, $inventario->cantidad_existencia);
        $this->assertCount(1, $inventario->movimientos, 'El inventario debe tener un movimiento');
        $this->assertEquals(10, $inventario->movimientos()->first()->cantidad_actual, 'La cantidad actual del movimiento de inventario debe ser 10');
    }

    /** @test */
    public function test_un_inventario_puede_incrementar_su_existencia()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material, 10);
        $inventario->incrementaExistencia(100);

        $this->assertEquals(110, $inventario->cantidad_existencia);
    }

    /** @test */
    public function test_un_inventario_puede_decrementar_su_existencia()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material, 100);
        $inventario->decrementaExistencia(10);

        $this->assertEquals(90, $inventario->cantidad_existencia);
    }

    /** @test */
    public function test_lanza_excepcion_cuando_la_existencia_no_es_suficiente()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $this->setExpectedException(SinExistenciaSuficienteException::class);

        $inventario = Inventario::creaInventario($area, $material, 100);
        $inventario->decrementaExistencia(130);

        $this->assertEquals(100, $inventario->cantidad_existencia);
        $this->assertCount(1, $inventario->movimientos);
    }

    /** @test */
    public function test_el_inventario_debe_registrar_movimientos()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        $inventario = Inventario::creaInventario($area, $material, 100);
        $inventario->incrementaExistencia(10);
        $inventario->decrementaExistencia(10);

        $this->assertEquals(100, $inventario->cantidad_existencia);
        $this->assertCount(3, $inventario->movimientos);
    }

    /** @test */
    public function test_transfiere_existencia_entre_inventarios()
    {
        $obra = factory(Obra::class)->create();
        $material = factory(Material::class)->create();
        $area_origen = factory(Area::class)->create(['id_obra' => $obra->id_obra]);
        $area_destino = factory(Area::class)->create(['id_obra' => $obra->id_obra]);

        // inventario origen con existencia de 1000
        $inventario_origen = Inventario::creaInventario($area_origen, $material, 1000);

        // inventario destino con existencia de 20
        $inventario_destino = Inventario::creaInventario($area_destino, $material, 20);

        $inventario_origen->transferirA($inventario_destino, 200);

        $this->assertEquals(800, $inventario_origen->cantidad_existencia, 'La existencia en origen no es 800');
        $this->assertCount(2, $inventario_origen->movimientos, 'El numero de movimientos en origen debe ser 2');

        $this->assertEquals(220, $inventario_destino->cantidad_existencia, 'La existencia en destino debe ser 220');
        $this->assertCount(2, $inventario_destino->movimientos, 'El numero de movimientos en destino debe ser 2');
    }
}
