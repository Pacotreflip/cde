<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\AreasTipo;

class EvaluacionCalidadController extends Controller
{
    /**
     * @var AreasTipo
     */
    protected $tipos_area;

    /**
     * @param AreasTipo $tipos_area
     */
    public function __construct(AreasTipo $tipos_area)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->tipos_area = $tipos_area;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $tipo = $this->tipos_area->getById($id);

        return view('tipos.evaluacion-calidad')
            ->withTipo($tipo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        foreach ($request->get('materiales', []) as $id_material => $material) {
            $this->tipos_area->getById($id)->materiales()->updateExistingPivot($id_material, ['se_evalua' => $material['evalua']]);
        }

        return back();
    }
}
