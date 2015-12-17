<?php

namespace Ghi\Http\Controllers\AreasTipo;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\AreasTipo;

class EvaluacionCalidadController extends Controller
{
    /**
     * @var AreasTipo
     */
    protected $areas_tipo;

    /**
     * @param AreasTipo $areas_tipo
     */
    public function __construct(AreasTipo $areas_tipo)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->areas_tipo = $areas_tipo;

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
        $tipo = $this->areas_tipo->getById($id);

        return view('areas-tipo.evaluacion-calidad')
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
        $area_tipo = $this->areas_tipo->getById($id);

        foreach ($request->get('materiales', []) as $id_material => $se_evalua) {
            $area_tipo->materialesRequeridos()
                ->where('id_material', $id_material)
                ->update($se_evalua);
        }

        return back();
    }
}
