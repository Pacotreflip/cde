<?php
namespace Ghi\Equipamiento\Cierres;
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
 * Description of Cierres
 *
 * @author EMartinez
 */
class Cierres {
    
    public function buscar($busqueda){
        $areas = Area::has('materialesRequeridos')->get();
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
    
    public function generarCierre(array $data, Obra $obra){
        DB::connection('cadeco')->beginTransaction();
        $cierre = $this->creaCierre($data, $obra);

        foreach ($data['id_area'] as $id_area) {
            $cierre_partida = new CierrePartida();
            $cierre_partida->id_cierre = $cierre->id;
            $cierre_partida->id_area = $id_area;
            $cierre_partida->save();
            
            $asignacion_item_validaciones = DB::connection('cadeco')
            ->table('Equipamiento.asignacion_items')
            ->join('Equipamiento.asignacion_item_validacion', 'Equipamiento.asignacion_items.id','=','Equipamiento.asignacion_item_validacion.id_item_asignacion')
            ->where('id_area_destino', $id_area)
            ->get();
            
            foreach($asignacion_item_validaciones as $asignacion_item_validacion){
                $cierre_partida_asignacion = new CierrePartidaAsignacion();
                $cierre_partida_asignacion->id_cierre_partida = $cierre_partida->id;
                $cierre_partida_asignacion->id_asignacion_item_validacion = $asignacion_item_validacion->id;
                $cierre_partida_asignacion->save();
            }
            
            if ($cierre_partida->cierre_partida_asignacion->count() === 0) {
                DB::connection('cadeco')->rollback();
                throw new \Exception("Hubo un error al registrar el cierre de áreas..");
            }
            
        }

        if ($cierre->partidas->count() === 0) {
            DB::connection('cadeco')->rollback();
            throw new \Exception("Hubo un error al registrar el cierre de áreas.");
        }

        $cierre->save();


        DB::connection('cadeco')->commit();
    }
    
    private function creaCierre($datos, $obra){
        $cierre = new Cierre();
        $cierre->obra()->associate($obra);
        $cierre->id_usuario = Auth::user()->idusuario;
        $cierre->observaciones = $datos["observaciones"];
        $carbon = new \Carbon\Carbon();
        $cierre->fecha_cierre = $carbon->now();
        $cierre->save();

        return $cierre;
    }
}
