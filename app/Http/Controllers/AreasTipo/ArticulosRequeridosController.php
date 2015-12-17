<?php

namespace Ghi\Http\Controllers\AreasTipo;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Ghi\Equipamiento\Moneda;
use Illuminate\Http\Request;
use Ghi\Equipamiento\TipoCambio;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\AreasTipo;
use Ghi\Equipamiento\Articulos\Materiales;
use Ghi\Equipamiento\Areas\MaterialRequerido;

class ArticulosRequeridosController extends Controller
{
    protected $tipos_area;
    protected $articulos;

    /**
     * @param AreasTipo $tipos_area
     * @param Materiales $articulos
     */
    public function __construct(AreasTipo $tipos_area, Materiales $articulos)
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
        $except = $tipo->materialesRequeridos->pluck('id_material')->all();

        if ($request->has('buscar')) {
            $articulos = $this->articulos->buscar($request->get('buscar'), 50, $except);
        } else {
            $articulos = $this->articulos->getAllPaginated(50, $except);
        }

        return view('areas-tipo.requerimientos.seleccion-articulos')
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

        foreach ($request->get('materiales') as $id_material) {
            $tipo->agregaArticuloRequerido($id_material);
        }

        return redirect()->route('requerimientos.edit', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $tipo = $this->tipos_area->getById($id);
        $monedas = Moneda::lists('nombre', 'id_moneda');
        $tipo_cambio = Moneda::where('nombre', 'DOLARES')->first()->tipoCambioMasReciente();

        return view('areas-tipo.articulos-requeridos')
            ->withTipo($tipo)
            ->withMonedas($monedas)
            ->withTipoCambio($tipo_cambio->cambio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $tipo = $this->tipos_area->getById($id);
        $articulos = $request->get('articulos', []);

        if ($this->seEliminanArticulos($request)) {
            $tipo->quitaMaterialesRequeridos($request->get('selected_articulos', []));
        }

        $articulos = $this->quitaEliminados($articulos, $request->get('selected_articulos', []));
        $articulos = $this->asignaValoresDefault($articulos);

        foreach ($articulos as $id_articulo => $articulo) {
            MaterialRequerido::find($id_articulo)->update($articulo);
        }

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
     * @param  array $eliminados Articulos que se eliminaron.
     * @return array Articulos filtrados.
     */
    protected function quitaEliminados($articulos, $eliminados)
    {
        return array_except($articulos, $eliminados);
    }

    /**
     * Asigna valores default para los campos que pueden ser nulos o no existir.
     *
     * @param $articulos
     * @return array
     */
    protected function asignaValoresDefault($articulos)
    {
        return collect($articulos)->map(function ($articulo) {
            $articulo['cantidad_comparativa'] = $articulo['cantidad_comparativa'] ?: null;
            $articulo['precio_comparativa'] = $articulo['precio_comparativa'] ?: null;
            $articulo['id_moneda'] = $articulo['id_moneda'] ?: null;
            $articulo['id_moneda_comparativa'] = $articulo['id_moneda_comparativa'] ?: null;

            if (! array_key_exists('existe_para_comparativa', $articulo)) {
                $articulo['existe_para_comparativa'] = 0;
            }

            return $articulo;
        })->all();
    }
}
