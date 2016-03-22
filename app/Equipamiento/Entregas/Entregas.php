<?php
namespace Ghi\Equipamiento\Entregas;
use Ghi\Core\Contracts\Context;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Core\Models\Obra;
use Illuminate\Support\Facades\Auth;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Entregas
 *
 * @author EMartinez
 */
class Entregas {
    
    public function buscar($busqueda){
        //$areas = Area::has('cierre_partida')->get();
        $areas = Area::all();
        $ids = [];
        foreach($areas as $area){
            if(stripos($area->getRutaAttribute(), $busqueda)!==FALSE){
                $ids[] = $area->id;
            }
        }
        $ids_cadena = implode(",", $ids);
        if($ids_cadena != ""){
            return Area::where(function ($query) use($ids_cadena) {
                    $query->whereRaw('id IN ('.$ids_cadena.')');
                })
                ->orderBy('nombre')->get()
                ;
        }else{
            return [];
        }
    }
    
    public function generarEntrega(array $data, Obra $obra){
        DB::connection('cadeco')->beginTransaction();
        $entrega = $this->creaEntrega($data, $obra);

        foreach ($data['id_area'] as $id_area) {
            $entrega_partida = new EntregaPartida();
            $entrega_partida->id_entrega = $entrega->id;
            $area =  Area::find($id_area);
            if($area->cierre_partida){
                $id_cierre_partida = $area->cierre_partida->id;
            }else{
                DB::connection('cadeco')->rollback();
                throw new \Exception("Hubo un error al registrar la entrega de áreas.");
            }
            
            
            $entrega_partida->id_cierre_partida = $id_cierre_partida;
            $entrega_partida->save();
            
            
            
        }

        if ($entrega->partidas->count() === 0) {
            DB::connection('cadeco')->rollback();
            throw new \Exception("Hubo un error al registrar el cierre de áreas.");
        }

        $entrega_partida->save();


        DB::connection('cadeco')->commit();
    }
    
    private function creaEntrega($datos, $obra){
        $entrega = new Entrega();
        $entrega->obra()->associate($obra);
        $entrega->id_usuario = Auth::user()->idusuario;
        $entrega->fecha_entrega = $datos["fecha_entrega"];
        $entrega->entrega = $datos["entrega"];
        $entrega->recibe = $datos["recibe"];
        $entrega->observaciones = $datos["observaciones"];
        $entrega->concepto = $datos["concepto"];
        $entrega->save();

        return $entrega;
    }
}
