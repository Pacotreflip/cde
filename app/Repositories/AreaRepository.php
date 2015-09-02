<?php

namespace Ghi\Repositories;

use Ghi\Area;
use Ghi\Subtipo;
use Illuminate\Support\Facades\DB;

class AreaRepository
{
    /**
     * Obtiene un area por su id
     *
     * @param $id
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
        return Area::defaultOrder()->withDepth()->get();
    }

    /**
     * Obtiene las areas que son raiz
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNivelesRaiz()
    {
        return Area::whereIsRoot()->defaultOrder()->get();
    }

    /**
     * Obtiene una lista de subtipos como un arreglo
     *
     * @return array
     */
    public function getListaSubtipos()
    {
        $subtipos = SubTipo::with(['tipo'])->orderBy('nombre')->get();

        $lista = [null => 'No Aplica'];
        foreach ($subtipos->sortBy(['tipo.nombre', 'nombre']) as $subtipo) {
            $lista[$subtipo->id] = $subtipo->tipo->nombre." - ".$subtipo->nombre;
        }

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
     * Elimina un area
     *
     * @param Area $area
     * @return bool
     */
    public function delete(Area $area)
    {
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

    public function agregaNivelDentro(Area $area = null)
    {

    }

    public function agregaNivel()
    {

    }

    public function subeNivel(Area $area)
    {

    }

    public function bajaNivel(Area $area)
    {

    }

    /**
     * Obtiene las areas descendientes de otra area
     *
     * @param $id
     * @return mixed
     */
    public function getDescendientesDe($id)
    {
        $ids = $this->getIdsDescendientesDe($id);

        return Area::whereIn('id', $ids)->get();
    }

    //    /**
//     * @param $nivel
//     * @return mixed
//     */
//    protected function getIdsByNivelProfundidad($nivel)
//    {
//        return DB::connection('equipamiento')->table('areas')
//            ->select(DB::raw('areas.id'))
//            ->leftJoin('areas as parent', 'areas.lft', 'BETWEEN', DB::raw('parent.lft and parent.rgt'))
//            ->groupBy('areas.nombre')
//            ->having(DB::raw('COUNT(1) - 1'), '=', $nivel)
//            ->orderBy('areas.lft')
//            ->lists('id');
//    }
}