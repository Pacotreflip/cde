<?php

use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Recepciones\RecibeArticulos;

class RecibeArticulosTest extends TestCase
{
    use \CadecoDatabaseTransactions;

    /** @test */
    public function test_recibe_articulos()
    {
        $recepcion = factory(Recepcion::class)->make();
        
        $data = array_merge($recepcion->toArray(), [
            'proveedor' => $recepcion->id_empresa,
            'orden_compra' => $recepcion->id_orden_compra,
            'area_almacenamiento' => $recepcion->id_area_almacenamiento,
        ]);

        $obra = $recepcion->obra;

        $data['materiales'] = $this->creaMaterialesParaRecibir(2);

        $recepcion = (new RecibeArticulos($data, $obra))->save();

        $this->assertCount(2, $recepcion->items);
    }

    /** @test */
    public function test_lanza_excepcion_al_crear_una_recepcion_sin_articulos()
    {
        $this->setExpectedException(\Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException::class);

        $recepcion = factory(Recepcion::class)->make();
        
        $data = array_merge($recepcion->toArray(), [
            'proveedor' => $recepcion->id_empresa,
            'orden_compra' => $recepcion->id_orden_compra,
            'area_almacenamiento' => $recepcion->id_area_almacenamiento,
        ]);

        $obra = $recepcion->obra;

        $data['materiales'] = [];

        $recepcion = (new RecibeArticulos($data, $obra))->save();
    }

    /**
     * Crea una coleccion de materiales para recibir.
     * 
     * @param  int $numero
     * 
     * @return array
     */
    protected function creaMaterialesParaRecibir($numero)
    {
        $materiales = [];

        for ($i = 1; $i <= $numero; $i++) {
            $materiales[] = [
                'id' => factory(Material::class)->create()->id_material,
                'cantidad_recibir' => mt_rand(1, 99999),
                'precio' => mt_rand(50, 99999),
            ];
        }

        return $materiales;
    }
}
