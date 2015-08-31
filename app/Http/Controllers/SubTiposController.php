<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests\CreateSubtipoRequest;
use Ghi\Http\Requests\UpdateSubtipoRequest;
use Ghi\Subtipo;
use Ghi\Tipo;
use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class SubTiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $tipo_id
     * @return Response
     */
    public function create($tipo_id)
    {
        $tipo = Tipo::findOrFail($tipo_id);

        return view('subtipos.create')->withTipo($tipo);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSubtipoRequest $request
     * @param $tipo_id
     * @return Response
     */
    public function store(CreateSubtipoRequest $request, $tipo_id)
    {
        $tipo = Tipo::findOrFail($tipo_id);
        $subtipo = new Subtipo($request->all());

        $tipo->subtipos()->save($subtipo);

        Flash::success('El subtipo fue agregado');

        return redirect()->route('tipos.edit', [$tipo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $tipo_id
     * @param $subtipo_id
     * @return Response
     */
    public function edit($tipo_id, $subtipo_id)
    {
        $tipo    = Tipo::findOrFail($tipo_id);
        $subtipo = Subtipo::findOrFail($subtipo_id);

        return view('subtipos.edit')
            ->withTipo($tipo)
            ->withSubtipo($subtipo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubtipoRequest $request
     * @param $tipo_id
     * @param $subtipo_id
     * @return Response
     */
    public function update(UpdateSubtipoRequest $request, $tipo_id, $subtipo_id)
    {
        $tipo    = Tipo::findOrFail($tipo_id);
        $subtipo = Subtipo::findOrFail($subtipo_id);

        $subtipo->update($request->all());

        Flash::success('Los cambios fueron guardados.');

        return redirect()->route('tipos.edit', [$tipo]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $tipo_id
     * @param $subtipo_id
     * @return Response
     */
    public function destroy($tipo_id, $subtipo_id)
    {
        $subtipo = Subtipo::findOrFail($subtipo_id);
        $subtipo->delete();

        Flash::message('El subtipo de area fue borrado.');

        return redirect()->route('tipos.edit', [$tipo_id]);
    }
}
