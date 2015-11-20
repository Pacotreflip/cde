<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
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
     * @param MaterialRepository $articulos
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

        $articulos = $this->asignaValoresDefault($articulos);

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

    /**
     * Asigna valores default para los campos que pueden ser nulos o no existir.
     *
     * @param $articulos
     * @return array
     */
    private function asignaValoresDefault($articulos)
    {
        return collect($articulos)->map(function ($articulo) {
            $articulo['cantidad_comparativa'] = $articulo['cantidad_comparativa'] ?: null;
            $articulo['precio_comparativa'] = $articulo['precio_comparativa'] ?: null;

            if ( ! array_key_exists('existe_para_comparativa', $articulo)) {
                $articulo['existe_para_comparativa'] = 0;
            }

            return $articulo;
        })->all();
    }
}
