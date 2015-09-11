<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\TipoAreaRepository;
use Ghi\Equipamiento\Articulos\MaterialRepository;

class AsignacionRequerimientosController extends Controller
{
    /**
     * @var TipoAreaRepository
     */
    protected $tipos_area;

    /**
     * @var [type]
     */
    protected $articulos;

    /**
     * @param TipoAreaRepository $tipos_area
     */
    public function __construct(TipoAreaRepository $tipos_area, MaterialRepository $articulos)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->tipos_area = $tipos_area;
        $this->articulos = $articulos;

        parent::__construct();
    }

    /**
     * Muestra un listado de articulos para seleccionar como requeridos
     * 
     * @param  int  $id
     * @param  Request $request
     * @return Response
     */
    public function create($id, Request $request)
    {
        $tipo = $this->tipos_area->getById($id);
        $except = $tipo->materiales->pluck('id_material')->all();

        if ($request->has('buscar')) {
            $articulos = $this->articulos->buscar($request->get('buscar'), 50, $except);
        } else {
            $articulos = $this->articulos->getAllPaginated(50, $except);
        }

        return view('tipos.requerimientos.seleccion-articulos')
            ->withTipo($tipo)
            ->withArticulos($articulos);
    }

    /**
     * Agrega articulos como requeridos
     * 
     * @param  int  $id
     * @param  Request $request
     * @return Response
     */
    public function store($id, Request $request)
    {
        $tipo = $this->tipos_area->getById($id);
        
        $tipo->materiales()->attach($request->get('articulos'), ['cantidad' => 1]);

        return redirect()->route('requerimientos.edit', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipo = $this->tipos_area->getById($id);

        return view('tipos.requerimientos.edit')
            ->withTipo($tipo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $tipo = $this->tipos_area->getById($id);
        $articulos = $request->get('articulos', []);

        if ($request->has('action')) {
            $seleccionados = array_keys($request->get('selected', []));
            $articulos = array_except($articulos, $seleccionados);
        }

        $tipo->materiales()->sync($articulos);

        Flash::success('Los cambios fueron guardados');

        return redirect()->route('requerimientos.edit', [$id]);
    }
}
