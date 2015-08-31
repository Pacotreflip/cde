<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests\CreateTipoRequest;
use Ghi\Http\Requests\UpdateTipoRequest;
use Ghi\Tipo;
use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class TiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $tipos = Tipo::OrderBy('nombre')->paginate(30);

        return view('tipos.index')->withTipos($tipos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTipoRequest $request
     * @return Response
     */
    public function store(CreateTipoRequest $request)
    {
        $tipo = Tipo::create($request->all());

        Flash::success('El nuevo tipo de area fue agregado.');

        return redirect()->route('tipos.edit', [$tipo]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipo = Tipo::findOrFail($id);

        return view('tipos.edit')->withTipo($tipo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTipoRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateTipoRequest $request, $id)
    {
        $tipo = Tipo::findOrFail($id);

        $tipo->update($request->all());

        Flash::success('Los cambios fueron guardados.');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tipo = Tipo::findOrFail($id);
        $tipo->delete();

        Flash::message('El tipo de area fue borrado.');

        return redirect()->route('tipos.index');
    }
}
