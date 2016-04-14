<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Transacciones\Item;
use Ghi\Equipamiento\Recepciones\Exceptions\RecepcionSinArticulosException;

class RecibeArticulosAlmacen
{
    protected $data;

    protected $obra;

    /**
     * @param array $data
     * @param Obra  $obra
     */
    public function __construct(array $data, Obra $obra)
    {
        $this->data = $data;
        $this->obra = $obra;
    }

    /**
     * Genera una recepcion de articulos.
     *
     * @return Recepcion
     * @throws \Exception
     */
    public function save()
    {
        try {
            DB::connection('cadeco')->beginTransaction();
            $proceso_sao = $this->procesoSAO();
            $recepcion = $this->creaRecepcion();
            foreach($proceso_sao as $transaccion){
                
            }
            foreach ($this->data['materiales'] as $item) {
                $material = Material::where('id_material', $item['id'])->first();
                $itemOrdenCompra = Item::findOrFail($item['id_item']);

                foreach ($item['destinos'] as $destino) {
                    $area = Area::findOrFail($destino['id']);

                    if (! $itemOrdenCompra->puedeRecibir($destino['cantidad'])) {
                        throw new \Exception("No es posible recibir la cantidad indicada para el articulo {$item['descripcion']}");
                    }

                    $recepcion->agregaMaterial($material, $destino['cantidad'], $itemOrdenCompra->id_item, $area);
                }
            }

            if ($recepcion->items->count() === 0) {
                throw new RecepcionSinArticulosException;
            }

            $recepcion->save();
            
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }

        return $recepcion;
    }

    /**
     * Crea una nueva recepcion.
     * 
     * @return Recepcion
     */
    protected function creaRecepcion()
    {
        $recepcion = new Recepcion($this->data);
        $recepcion->obra()->associate($this->obra);
        $recepcion->id_empresa = $this->data['proveedor'];
        $recepcion->id_orden_compra = $this->data['orden_compra'];
        $recepcion->creado_por = Auth::user()->usuario;
        $recepcion->id_usuario = Auth::user()->idusuario;
        $recepcion->save();

        return $recepcion;
    }
    
    protected function procesoSAO(){
        $transacciones = null;
        $datos_transacciones = $this->preparaDatosTransacciones();
        foreach($datos_transacciones["entradas_almacen"] as $datos_entrada){
            $transacciones [] = $this->creaEntradaAlmacen($datos_entrada);
            
        }
        foreach($datos_transacciones["transferencias_almacen"] as $datos_transferencia){
            $transacciones [] = $this->creaTransferenciaAlmacen($datos_transferencia);
        }
        
        return $transacciones;
    }
    protected function preparaDatosTransacciones(){
        $items_a_procesar = $this->data["materiales"];
        $items = "";
        foreach($items_a_procesar as $item_a_procesar){
            $cantidad_procesar = $this->acumuladoCantidadRecibir($item_a_procesar);
            $cantidad_disponible_entrada = $this->getCantidadDisponibleEntrada($item_a_procesar);
            $existencia_almacen = $this->getExistenciasAlmacen($item_a_procesar);
            //$datos_entrada = $this->getPartidasDisponiblesEntradaSAO($item_a_procesar["id_item"]);
            if(($cantidad_procesar-($cantidad_disponible_entrada + $existencia_almacen))>0.01){
               throw new \Exception("No es posible recibir la cantidad indicada para el articulo {$item_a_procesar['descripcion']} no hay existencias suficientes en el SAO: Disponible Entrada: {$cantidad_disponible_entrada}; Disponible Transferencia: {$existencia_almacen}");
            }
            //dd($this->obra->id_obra, $this->data,$cantidad_disponible_entrada, $existencia_almacen);
            
            
            while($cantidad_procesar > 0){
                if($cantidad_procesar <= $datos_entrada["cantidad_pendiente"]){
                
                }else{

                }
            }
            
        }
        
        //dd($partidas_entrada_sao);
        #obtener cantidad pendiente del material en esa oc
        
        return $datos;
    }
    protected function getCantidadDisponibleEntrada($item_procesar){
         $resultado = DB::connection("cadeco")->select('
        SELECT
            c.[cantidad] - c.[surtida] AS cantidad_pendiente
            
        FROM
            ItemsComprados as c join items as i on(i.id_item = c.id_item)
        WHERE
            i.id_item = '.$item_procesar["id_item"].' 
        
        
        ');
        return $resultado[0]->cantidad_pendiente;
    }
    protected function getExistenciasAlmacen($item_procesar){
         $resultado = DB::connection("cadeco")->select('
        SELECT
              sum(inventarios.saldo) as existencias
        FROM
            inventarios join items  on(items.id_material = inventarios.id_material)
            join almacenes on(almacenes.id_almacen = inventarios.id_almacen)
        WHERE
            almacenes.id_obra = '.$this->obra->id_obra.' AND
            items.id_item = '.$item_procesar["id_item"].' 
        
        ');
        return $resultado[0]->existencias;
    }
    protected function getPartidasDisponilesTransferenciaSAO($item_procesar){
        
    }
    protected function acumuladoCantidadRecibir($item_procesar){
        $acumulado = 0;
        foreach ($item_procesar["destinos"] as $destinos){
            $acumulado += $destinos["cantidad"];
        }
        return $acumulado;
    }
    protected function getPartidasDisponiblesEntradaSAO($id_item_oc){
        
        $resultado = DB::connection("cadeco")->select('
        SELECT
              c.[numero_parte]
            , c.[descripcion]
            , c.[unidad_compra]
            , CONVERT(VARCHAR(10), c.fecha, 105) as fecha
            , c.[cantidad] - c.[surtida] AS cantidad_pendiente
            , c.surtida as cantidad_surtida
            , i.id_material as id_material
            , i.unidad as unidad
            , i.cantidad_material
            , i.precio_unitario
            , i.anticipo
            , i.saldo
            , i.id_item
        FROM
            ItemsComprados as c join items as i on(i.id_item = c.id_item)
        WHERE
            i.id_item = '.$id_item_oc.' 
            AND ( c.cantidad > c.surtida )
        ORDER BY
            c.id_material
        , c.numero_entrega;  
            ');
        return $resultado;
    }
    protected function creaEntradaAlmacen($datos_entrada)
    {
        $entrada_almacen = new EntradaAlmacen($datos_entrada);
        $entrada_almacen->obra()->associate($this->obra);
        $entrada_almacen->save();
        $partidas_entrada = $this->creaPartidasEntradaAlmacen($datos_entrada["partidas"]);
        $entrada_almacen->items()->saveMany($partidas_entrada);
        $this->ejecuta_procedimiento_entrada($entrada_almacen);
        $this->actualiza_entregas($entrada_almacen);
        return $entrada_almacen;
    }
    
    protected function creaPartidasEntradaAlmacen($datos_partidas_entrada){
        $partidas = null;
        foreach($datos_partidas_entrada as $datos_partida_entrada){
            $partidas[] = new EntradaAlmacenItem($datos_partida_entrada);
        }
        return $partidas;
    }
    
    protected function ejecuta_procedimiento_entrada($objEntrada){
        foreach($objEntrada->items as $item){
            $resultado = DB::connection("cadeco")->select('DECLARE @RC int
                    DECLARE @id_item int
                    EXECUTE @RC = [sp_entrada_material] 
                    '.$item->id.' 
                    SELECT @RC as res');
            if($resultado["res"] != 0){
                throw new \Exception("Hubo un error al aplicar el procedimiento de entrada de almacén para el material:" . $item->material->descripcion);
            }
            
        }
    }
    
    protected function actualiza_entregas($objEntrada){
        foreach($objEntrada->items as $item){
            DB::table("entregas")
                ->where("id", $item->item_antecedente)
                ->increment("surtida", $item->cantidad);
        }
    }
    
    protected function creaTransferenciaAlmacen($datos_transferencia)
    {
        $transferencia_almacen = new TransferenciaAlmacen($datos_transferencia);
        $transferencia_almacen->save();
        $partidas_transferencia = $this->creaPartidasEntradaAlmacen($datos_transferencia["partidas"]);
        $transferencia_almacen->items()->saveMany($partidas_transferencia);
        $this->ejecuta_procedimiento_transferencia($transferencia_almacen);
        return $transferencia_almacen;
    }
    
    protected function creaPartidasTransferenciaAlmacen($datos_partidas_transferencia){
        $partidas = null;
        foreach($datos_partidas_transferencia as $datos_partida_transferencia){
            $partidas[] = new TransferenciaAlmacenItem($datos_partida_transferencia);
        }
        return $partidas;
    }
    
    protected function ejecuta_procedimiento_transferencia($objTransferencia){
        foreach($objTransferencia->items as $item){
            $resultado = DB::connection("cadeco")->select('
                DECLARE @RC int
                DECLARE @id_item int
                EXECUTE @RC = [dbo].[sp_salida_material] 
                    '.$item->id.' 
                SELECT @RC as res
            ');
            if($resultado["res"] != 0){
                throw new \Exception("Hubo un error al aplicar el procedimiento de transferencia de almacén para el material:" . $item->material->descripcion);
            }
        }
    }
    
    
}
