<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\AreaRepository;

class AreasController extends Controller
{
    protected $areas;

    public function __construct(AreaRepository $areas)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->areas = $areas;
    }

    public function index()
    {
        $areas = [];

        foreach ($this->areas->getAll() as $area) {
            $areas[] = [
                'id' => $area->id,
                'nombre' => $area->nombre,
                'depth' => $area->depth,
            ];
        }

        return response()->json($areas);
    }

    public function show($id)
    {
        $area = Area::with('materiales')->findOrFail($id);

        return response()->json($this->transformArea($area));
    }

    protected function transformArea(Area $area)
    {
        return [
            'id'          => $area->id,
            'clave'       => $area->clave,
            'nombre'      => $area->nombre,
            'materiales'  => $this->includeMateriales($area->materiales),
        ];
    }

    protected function includeMateriales($materiales)
    {
        $recursos = [];

        $materiales->map(function ($material) use (&$recursos) {
            $recursos[] = $this->transformMaterial($material);
        });
        
        return $recursos;
    }

    protected function transformMaterial($material)
    {
        return [
            'id'            => $material->id_material,
            'id_inventario' => $material->pivot->id,
            'numero_parte'  => $material->numero_parte,
            'descripcion'   => $material->descripcion,
            'unidad'        => $material->unidad,
            'existencia'    => $material->pivot->cantidad_existencia,
        ];
    }
}
