<?php

use Ghi\Equipamiento\Articulos\Unidad;
use Ghi\Equipamiento\Articulos\Factory;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Articulos\TipoMaterial;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArticuloTest extends TestCase
{
    use \CadecoDatabaseTransactions;

    /** @test */
    public function test_creacion_de_un_material_de_tipo_materiales()
    {
        $material = $this->nuevoMaterial(TipoMaterial::TIPO_MATERIALES);

        $this->assertInstanceOf(Material::class, $material);
        $this->assertEquals(TipoMaterial::TIPO_MATERIALES, $material->tipo_material->getTipo());
        $this->assertEquals(1, $material->equivalencia);
        $this->assertEquals(1, $material->marca);
    }

    /** @test */
    public function test_creacion_de_un_material_de_tipo_mano_obra()
    {
        $material = $this->nuevoMaterial(TipoMaterial::TIPO_MANO_OBRA);

        $this->assertInstanceOf(Material::class, $material);
        $this->assertEquals(TipoMaterial::TIPO_MANO_OBRA, $material->tipo_material->getTipo());
        $this->assertEquals(1, $material->equivalencia);
        $this->assertEquals(0, $material->marca);
    }

    /** @test */
    public function test_creacion_de_un_material_de_tipo_servicios()
    {
        $material = $this->nuevoMaterial(TipoMaterial::TIPO_SERVICIOS);

        $this->assertInstanceOf(Material::class, $material);
        $this->assertEquals(TipoMaterial::TIPO_SERVICIOS, $material->tipo_material->getTipo());
        $this->assertEquals(1, $material->equivalencia);
        $this->assertEquals(1, $material->marca);
    }

    /** @test */
    public function test_creacion_de_un_material_de_tipo_maquinaria()
    {
        $material = $this->nuevoMaterial(TipoMaterial::TIPO_MAQUINARIA);

        $this->assertInstanceOf(Material::class, $material);
        $this->assertEquals(TipoMaterial::TIPO_MAQUINARIA, $material->tipo_material->getTipo());
        $this->assertEquals(0, $material->equivalencia);
        $this->assertEquals(1, $material->marca);
        $this->assertNull($material->unidad_compra);
        $this->assertNotNull($material->unidad_capacidad);
    }

    /** @test */
    public function test_creacion_de_un_material_de_tipo_herramienta_y_equipo()
    {
        $material = $this->nuevoMaterial(TipoMaterial::TIPO_HERRAMIENTA_Y_EQUIPO);

        $this->assertInstanceOf(Material::class, $material);
        $this->assertEquals(TipoMaterial::TIPO_HERRAMIENTA_Y_EQUIPO, $material->tipo_material->getTipo());
        $this->assertEquals(1, $material->equivalencia);
        $this->assertEquals(0, $material->marca);
    }

    /** @test */
    public function test_agregar_material_en_familia()
    {
        $material = $this->nuevoMaterial();
        $familia = factory(Ghi\Equipamiento\Articulos\Familia::class)->create(['nivel' => '700.']);

        $familia->agregaMaterial($material);
        $this->assertTrue($material->isChildrenOf($familia));
    }

    /** @test */
    public function test_lanza_excepcion_al_agregar_un_material_a_familia_con_diferente_tipo()
    {
        $this->setExpectedException(Ghi\Equipamiento\Articulos\Exceptions\MaterialConDiferenteTipoException::class);

        $material = $this->nuevoMaterial(TipoMaterial::TIPO_SERVICIOS);
        $familia = factory(Ghi\Equipamiento\Articulos\Familia::class)->create(['nivel' => '800.']);

        $familia->agregaMaterial($material);
    }

    /** @test */
    public function test_lanza_excepcion_al_agregar_un_material_a_una_familia_llena()
    {
        $this->setExpectedException(Ghi\Equipamiento\Articulos\Exceptions\FamiliaLlenaException::class);

        $familia = factory(Ghi\Equipamiento\Articulos\Familia::class)->create(['nivel' => '900.']);
        $unidad = factory(Unidad::class)->create(['unidad' => 'TEST']);

        $materiales = factory(Material::class, 999)->make([
            'unidad' => $unidad->unidad,
            'unidad_compra' => $unidad->unidad
            ])->each(function ($material) use ($familia) {
                $familia->agregaMaterial($material);
            });
    }


    public function nuevoMaterial($tipo = TipoMaterial::TIPO_MATERIALES)
    {
        $factory = new Factory;
        $material = factory(Material::class)->make(['tipo_material' => $tipo]);
        $unidad = factory(Unidad::class)->create();

        return $factory->make(
            $material->descripcion,
            $material->descripcion_larga,
            $material->numero_parte,
            $unidad,
            $unidad,
            $tipo
        );
    }
}
