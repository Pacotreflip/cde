<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\TipoAreaRepository;
use Ghi\Equipamiento\Articulos\MaterialRepository;

class ArticulosRequeridosController extends Controller
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
     * Muestra un listado de articulos para seleccionar como requeridos.
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
     * Agrega articulos como requeridos.
     * 
     * @param  int  $id
     * @param  Request $request
     * @return Response
     */
    public function store($id, Request $request)
    {
        $tipo = $this->tipos_area->getById($id);
        
        foreach ($request->get('materiales') as $material) {
            $tipo->requiereArticulo($material);
        }

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

        return view('tipos.articulos-requeridos')
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

        if ($this->seEliminanArticulos($request)) {
            $articulos = $this->filtraArticulos($articulos, $request->get('selected_articulos', []));
        }

        $tipo->requiereArticulo($articulos);

        Flash::success('Los cambios fueron guardados');

        return redirect()->route('requerimientos.edit', [$id]);
    }

    /**
     * Indica si existe una accion de eliminar articulos en el request.
     * 
     * @param  Request $request
     * @return bool
     */
    protected function seEliminanArticulos($request)
    {
        return $request->has('action') and $request->get('action') == 'delete_selected';
    }

    /**
     * Devuelve una lista de articulos filtrada por una segunda lista.
     * 
     * @param  array $articulos Articulos a filtrar.
     * @param  array $filtro Articulos que se filtraran de la lista.
     * @return array Articulos filtrados.
     */
    protected function filtraArticulos($articulos, $filtro)
    {
        return array_except($articulos, $filtro);
    }
}
