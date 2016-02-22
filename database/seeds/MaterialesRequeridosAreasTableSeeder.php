<?php

use Illuminate\Database\Seeder;
use \Ghi\Equipamiento\Areas\AreaTipo;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
use Ghi\Equipamiento\Areas\MaterialRequerido;
use Ghi\Equipamiento\Areas\Area;
class MaterialesRequeridosAreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = AreaTipo::all();
        foreach($tipos as $tipo){
            $tipo->areas->each(function($area) use($tipo)
            {
                $materiales_requeridos_tipo = $area->tipo->materialesRequeridos;
                foreach($materiales_requeridos_tipo as $material_requerido_tipo){
                    $material_requerido = MaterialRequeridoArea::whereRaw("id_area = ". $area->id ." and id_material_requerido = ". $material_requerido_tipo->id)->first();
    //meter despues lo de la validación de artículos asignados
                    if($material_requerido != null){
                        if($material_requerido->cantidadMaterialesAsignados()>0){
                            $material_requerido->desvinculaMaterialRequeridoAreaTipo();
                        }else{
                            $material_requerido->delete();
                        }
                    }

                }
                
                $materiales_requeridos = [];
                $materiales_requeridos_candidatos = $area->getArticuloRequeridoDesdeAreaTipo($tipo);
                foreach($materiales_requeridos_candidatos as $material_requerido_candidato){
                    $material_requerido = $area->materialesRequeridos->where("id_material", $material_requerido_candidato->id_material)->first();
                    if($material_requerido != null){
                        if($material_requerido->cantidad_requerida == $material_requerido_candidato->cantidad_requerida){
                            $material_requerido->id_material_requerido = $material_requerido_candidato->id_material_requerido;
                            $material_requerido->save();
                        }
                    }else{
                        $materiales_requeridos[] = $material_requerido_candidato;
                    }
                }
                $area->materialesRequeridos()->saveMany($materiales_requeridos);
            });
        }
        
    }
}
