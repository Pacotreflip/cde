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
        //$areas = Area::has('materialesRequeridos')->get();
        $areas = Area::join("Equipamiento.reporte_materiales_requeridos_area", "Equipamiento.areas.id", "=", "Equipamiento.reporte_materiales_requeridos_area.id_area")
                ->whereRaw("Equipamiento.reporte_materiales_requeridos_area.ruta_area like'%{$busqueda}%'")
                ->groupBy(DB::raw("areas.id, areas.nombre, areas.clave"))
                ->select(DB::raw("areas.id, areas.nombre, areas.clave"))->get();
        $areas_almacenes = Area::where('es_almacen',"=", 1)->get();
        //$areas_collect = collect($areas);
        //$ids = $areas_collect->groupBy("id")->keys()->toArray();
        $ids = [];
        foreach($areas_almacenes as $area_almacen){
            if(stripos($area_almacen->getRutaAttribute(), $busqueda)!==FALSE){
                $ids[] = $area_almacen->id;
            }
        }
        $ids_cadena = implode(",", $ids);
        if($ids_cadena != ""){
            $areas_almacen = Area::where(function ($query) use($ids_cadena) {
                $query->whereRaw('id IN ('.$ids_cadena.')');
            })
            ->orderBy('nombre')->get()
            ;
            foreach($areas_almacen as $area_push){
                $areas->push($area_push);
            }
        }
        return $areas;
    }
    
    public function generarCierre(array $data, Obra $obra){
        DB::connection('cadeco')->beginTransaction();
        $cierre = $this->creaCierre($data, $obra);

        foreach ($data['id_area'] as $id_area) {
            $area = Area::findOrFail($id_area);
            $cierre_partida = new CierrePartida();
            $cierre_partida->id_cierre = $cierre->id;
            $cierre_partida->id_area = $id_area;
            $cierre_partida->save();
            
            if($area->es_almacen == 0){
            
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
            }
            
            if ($cierre_partida->cierre_partida_asignacion->count() == 0 && $area->es_almacen == 0) {
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
    public function cancelar($datos, Obra $obra)
    {
        $this->id = $datos["id"];
        $this->datos = $datos;
        $this->obra = $obra;
    
        try {
            DB::connection('cadeco')->beginTransaction();
            $cierre = Cierre::findOrFail($this->id);
            $this->registraCancelacion($cierre);
            $cierre->delete();
            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }
    }
    
    protected function registraCancelacion($cierre){
        $carbon = new \Carbon\Carbon();
        DB::connection("cadeco")->table('Equipamiento.cancelaciones')->insert(
            [
                'id_obra'=>$this->obra->id_obra,
                'motivo'=>$this->datos["motivo"],
                'created_at'=>$carbon->now(),
                'updated_at'=>$carbon->now(),
                'numero_folio_cierre' => $cierre->numero_folio, 
                'id_usuario' => Auth::id()]
        );
    }
}
