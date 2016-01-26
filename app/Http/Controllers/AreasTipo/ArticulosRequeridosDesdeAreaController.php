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
use Illuminate\Support\Facades\Validator;
use Ghi\Http\Requests\MuestraArticulosAreaSeleccionadaRequest;
use Illuminate\Support\Facades\DB;

class ArticulosRequeridosDesdeAreaController extends Controller
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

        if ($request->has('buscar')) {
            $tipos_area = $this->tipos_area->buscar($request->get('buscar'), 50);
            //$tipos_area = $this->tipos_area->getAllPaginated(50);
        } else {
            $tipos_area = $this->tipos_area->getAllPaginated(50);
        }

        return view('areas-tipo.requerimientos.seleccion-areas', ["tipos_area"=> $tipos_area])
            ->withTipo($tipo)
            ;
    }
    
    public function muestraMateriales($id, MuestraArticulosAreaSeleccionadaRequest $request)
    {
        $tipo = $this->tipos_area->getById($id);
        $id_tipo_area = $request->id_tipo_area;
        $tipo_origen = $this->tipos_area->getById($id_tipo_area);
        $materiales = DB::connection("cadeco")->select("SELECT        Equipamiento.materiales_requeridos.id_tipo_area, dbo.materiales.numero_parte, dbo.materiales.descripcion, dbo.materiales.unidad, 
                         Equipamiento.materiales_requeridos.cantidad_requerida, Equipamiento.materiales_requeridos.precio_estimado AS precio, dbo.monedas.nombre AS moneda, 
                         monedas_1.nombre AS moneda_comparativa, Equipamiento.materiales_requeridos.precio_comparativa, 
                         Equipamiento.materiales_requeridos.cantidad_comparativa
FROM            dbo.materiales INNER JOIN
                         Equipamiento.materiales_requeridos ON dbo.materiales.id_material = Equipamiento.materiales_requeridos.id_material INNER JOIN
                         dbo.monedas ON Equipamiento.materiales_requeridos.id_moneda = dbo.monedas.id_moneda INNER JOIN
                         dbo.monedas AS monedas_1 ON Equipamiento.materiales_requeridos.id_moneda_comparativa = monedas_1.id_moneda
WHERE        (Equipamiento.materiales_requeridos.id_tipo_area = $id_tipo_area)");
        return view('areas-tipo.requerimientos.seleccion-articulos-area', ["articulos"=> $materiales, "tipo_origen"=>$tipo_origen])
            ->withTipo($tipo)
            ;
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
        $idarea_tipo_origen = $request->idarea_tipo_origen;
        
        $materiales = DB::connection("cadeco")->select("SELECT  $id as id_tipo_area, id_material, cantidad_requerida, id_moneda, precio_estimado, se_evalua, cantidad_comparativa,
id_moneda_comparativa, precio_comparativa, existe_para_comparativa
FROM           
                         Equipamiento.materiales_requeridos
WHERE        (Equipamiento.materiales_requeridos.id_tipo_area = $idarea_tipo_origen)");
        $materialRequerido = null;

        foreach ($materiales as $material){
            $materialRequerido = new MaterialRequerido();
            $materialRequerido->id_tipo_area = $material->id_tipo_area;
            $materialRequerido->id_material = $material->id_material;
            $materialRequerido->cantidad_requerida = $material->cantidad_requerida;
            $materialRequerido->id_moneda = $material->id_moneda;
            $materialRequerido->precio_estimado = $material->precio_estimado;
            $materialRequerido->se_evalua = $material->se_evalua;
            $materialRequerido->cantidad_comparativa = $material->cantidad_comparativa;
            $materialRequerido->id_moneda_comparativa = $material->id_moneda_comparativa;
            $materialRequerido->precio_comparativa = $material->precio_comparativa;
            $materialRequerido->existe_para_comparativa = $material->existe_para_comparativa;
            $materialRequerido->save();
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
