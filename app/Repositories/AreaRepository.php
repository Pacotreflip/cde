<?php

namespace Ghi\Repositories;

use Ghi\Area;
use Illuminate\Support\Facades\DB;

class AreaRepository
{
    public function getById($id)
    {
        return Area::findOrFail($id);
    }

    public function getAll()
    {
        return Area::all();
    }

    /**
     * @param $nivel
     * @return mixed
     */
    protected function getIdsByNivelProfundidad($nivel)
    {
        return DB::connection('equipamiento')->table('areas')
            ->select(DB::raw('areas.id'))
            ->leftJoin('areas as parent', 'areas.lft', 'BETWEEN', DB::raw('parent.lft and parent.rgt'))
            ->groupBy('areas.nombre')
            ->having(DB::raw('COUNT(1) - 1'), '=', $nivel)
            ->orderBy('areas.lft')
            ->lists('id');
    }

    protected function getIdsDescendientesDe($id)
    {
        dd(DB::connection('equipamiento')->table('areas')
            ->select(DB::raw('areas.id'))
            ->leftJoin('areas as parent', 'areas.lft', 'BETWEEN', DB::raw('parent.lft and parent.rgt'))
            ->whereNotNull('parent.id')
            ->where('parent.id', $id)
            ->where('areas.id', '<>', $id)
            ->orderBy('areas.lft')
            ->toSQL());
        return DB::connection('equipamiento')->table('areas')
            ->select(DB::raw('areas.id'))
            ->leftJoin('areas as parent', 'areas.lft', 'BETWEEN', DB::raw('parent.lft and parent.rgt'))
            ->whereNotNull('parent.id')
            ->where('parent.id', $id)
            ->where('areas.id', '<>', $id)
            ->orderBy('areas.lft')
            ->lists('id');
    }

    /**
     * @return mixed
     */
    public function getNivelesRaiz()
    {
        $ids = $this->getIdsByNivelProfundidad(0);

        return Area::whereIn('id', $ids)
            ->orderBy('lft')
            ->get();
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
}