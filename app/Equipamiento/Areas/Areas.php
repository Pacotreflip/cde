<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Support\Facades\DB;
use Ghi\Core\Repositories\BaseRepository;

class Areas extends BaseRepository
{
    var $lista_areas = [];
    /**
     * Obtiene un area por su id
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return Area::findOrFail($id);
    }

    /**
     * Obtiene la estructura completa de areas
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Area::where('id_obra', $this->context->getId())
            ->defaultOrder()->withDepth()->get();
    }

    /**
     * Obtiene las areas que son raiz
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNivelesRaiz()
    {
        return Area::where('id_obra', $this->context->getId())
            ->whereIsRoot()->defaultOrder()->get();
    }

    /**
     * Obtiene una lista de tipos de area como un arreglo
     *
     * @return array
     */
    public function getListaTipos()
    {
        return $tipos = AreaTipo::defaultOrder()->lists('nombre', 'id');

        return $lista;
    }

    /**
     * Obtiene una lista de areas como un arreglo
     *
     * @return array
     */
    public function getListaAreas()
    {
        $areas = $this->getAll();

        $lista = [null => 'Inicio'];
        foreach ($areas as $area) {
            $lista[$area->id] = str_repeat('- ', $area->depth).' '.$area->nombre;
        }

        return $lista;
    }
    
    
    
    /**
     * Obtiene una lista de areas cerrables como un arreglo
     *
     * @return array
     */
    public function getListaAreasCerrables()
    {
//        $areas = $this->getAll();
//
//        $i = 1;
//        foreach ($areas as $area) {
//            $lista[$i] = [
//                "id"=>$area->id,
//                "area"=>str_repeat('- ', $area->depth).' '.$area->nombre,
//                "cerrable"=>0,
//            ];
//            if($area->area_padre){
//                $lista[$i]["id_padre"] = $area->area_padre->id;
//            }
//            $i++;
//        }
        //Area::where('id_obra', $this->context->getId());
        //dd($this->context->getId());
        $areas = Area::whereRaw('parent_id is null and id_obra = ?', [$this->context->getId()])
                ->defaultOrder()->withDepth()->get();
        //$areas = Area::where("parent_id",null)->get();
        $area = $areas[0];
        $this->lista_areas[] = $this->areaArreglo($area);
        $this->obtieneHijos($area);

//        $i = 1;
//        foreach ($area->areas_hijas as $area) {
//            $lista[$i] = [
//                "id"=>$area->id,
//                "area"=>str_repeat('- ', $area->depth).' '.$area->nombre,
//                "cerrable"=>0,
//            ];
//            if($area->area_padre){
//                $lista[$i]["id_padre"] = $area->area_padre->id;
//            }
//            $i++;
//        }
        return $this->lista_areas;
    }
    
    public function areaArreglo(Area $area){
        $area_arreglo = [
            "id"=>$area->id,
            "area"=>str_repeat('- ', $area->depth).' '.$area->nombre,
            "cerrable"=>$area->esCerrable(),
            "profundidad"=>$area->depth,
        ];
        if($area->area_padre){
            $area_arreglo["id_padre"] = $area->area_padre->id;
        }
        return $area_arreglo;
    }
    
    public function obtieneHijos($area){
        $hijos = $area->areas_hijas()->defaultOrder()->withDepth()->get();
        foreach($hijos as $hijo){
            $this->lista_areas[] = $this->areaArreglo($hijo);
            if($hijo->areas_hijas){
                $this->obtieneHijos($hijo);
            }
        }
        
    }

    /**
     * Elimina un area
     *
     * @param Area $area
     * @return bool
     */
    public function delete(Area $area)
    {
        $area->materialesRequeridos()->delete();
        return $area->delete();
    }

    /**
     * Guarda los cambios de un area
     *
     * @param Area $area
     * @return bool|mixed
     */
    public function save(Area $area)
    {
        return $area->save();
    }

    /**
     * Obtiene las areas descendientes de otra area
     *
     * @param int $id
     * @return Area
     */
    public function getDescendientesDe($id)
    {
        $ids = $this->getIdsDescendientesDe($id);

        return Area::whereIn('id', $ids)->get();
    }

    /**
     * Obtiene las areas que son hijos de otra area.
     * 
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHijosDe($id)
    {
        //return $this->getById($id)->children()->defaultOrder()->get();
        $area = Area::find($id);
        return $area->areas_hijas()->defaultOrder()->get();
    }
}
