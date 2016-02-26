<?php
namespace Ghi\Equipamiento\Cierres;
use Ghi\Core\Contracts\Context;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Areas\Area;
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
}
