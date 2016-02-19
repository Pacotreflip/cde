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
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;

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
        $materiales_requeridos = [];
        foreach ($request->get('materiales') as $id_material) {
            $materiales_requeridos[] = $tipo->agregaArticuloRequerido($id_material);
        }
        $tipo->areas->each(function($area) use($materiales_requeridos)
        {
            foreach ($materiales_requeridos as $material_requerido) {
                $material_requerido_area[] = new MaterialRequeridoArea([
                    'id_material'=>$material_requerido->id_material,
                    'id_material_requerido'=>$material_requerido->id,
                    'cantidad_requerida'=>$material_requerido->cantidad_requerida,
                    'cantidad_comparativa'=>$material_requerido->cantidad_comparativa,
                    'existe_para_comparativa'=>$material_requerido->existe_para_comparativa,
                ]);
            }
            $area->materialesRequeridos()->saveMany($material_requerido_area);
        }); 
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
        
        $articulos_eliminar = $request->get('selected_articulos_eliminar', []);
        
        foreach ($articulos_eliminar as $id_articulo_eliminar) {
            MaterialRequeridoArea::where("id_material_requerido",$id_articulo_eliminar)->delete();
            $articulo_requerido = MaterialRequerido::findOrFail($id_articulo_eliminar);
            $articulo_requerido->delete();
        }

        if ($this->seEliminanArticulos($request)) {
            $tipo->quitaMaterialesRequeridos($request->get('selected_articulos', []));
        }

        $articulos = $this->quitaEliminados($articulos, $request->get('selected_articulos', []));
        $articulos = $this->asignaValoresDefault($articulos);

        foreach ($articulos as $id_articulo => $articulo) {
            $articulo_requerido = MaterialRequerido::findOrFail($id_articulo);
            if($articulo_requerido){
                $articulo_requerido->update($articulo);
                $materiales_requeridos_areas = MaterialRequeridoArea::where("id_material_requerido",$articulo_requerido->id)->get();
                //dd($materiales_requeridos_areas);
                foreach($materiales_requeridos_areas as $material_requerido_areas){
                    $material_requerido_areas->cantidad_requerida = $articulo_requerido->cantidad_requerida;
                    $material_requerido_areas->cantidad_comparativa = $articulo_requerido->cantidad_comparativa;
                    $material_requerido_areas->update();
                }
            }
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

            if (! array_key_exists('existe_para_comparativa', $articulo)) {
                $articulo['existe_para_comparativa'] = 0;
            }

            return $articulo;
        })->all();
    }
}
