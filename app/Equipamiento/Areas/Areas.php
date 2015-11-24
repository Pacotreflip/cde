<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Support\Facades\DB;
use Ghi\Core\Repositories\BaseRepository;

class Areas extends BaseRepository
{
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
        return $this->getById($id)->children()->defaultOrder()->get();
    }
}
