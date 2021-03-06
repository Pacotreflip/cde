<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Articulos\Unidad;
use Ghi\Equipamiento\Articulos\Factory;
use Ghi\Equipamiento\Articulos\Familia;
use Ghi\Equipamiento\Articulos\Clasificador;
use Ghi\Equipamiento\Articulos\TipoMaterial;
use Ghi\Http\Requests\CreateArticuloRequest;
use Ghi\Http\Requests\UpdateArticuloRequest;
use Ghi\Equipamiento\Articulos\Materiales;
use Ghi\Equipamiento\Articulos\ClasificadorRepository;
use Ghi\Equipamiento\Moneda;
use Ghi\Equipamiento\Compras\Compras;
use Illuminate\Support\Facades\File;
class ArticulosController extends Controller
{
    protected $materiales;
    protected $monedas;
    protected $clasificadores;

    /**
     * @param Materiales $materiales
     * @param ClasificadorRepository $clasificadores
     */
    public function __construct(Materiales $materiales, ClasificadorRepository $clasificadores, Moneda $monedas)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->materiales = $materiales;
        $this->clasificadores = $clasificadores;
        $this->monedas = $monedas;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $materiales = $this->materiales->buscar($request->get('buscar'), 30);
        $idobra = $this->getObraEnContexto()->id_obra;
        return view('articulos.index')
            ->with("idobra",$idobra)
            ->withMateriales($materiales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $unidades = [null => 'Seleccione una opcion'] + $this->materiales->getListaunidades();
        $familias = $this->materiales->getListaFamilias(TipoMaterial::TIPO_MATERIALES);
        $tipos = $this->materiales->getListaTipoMateriales();
        $clasificadores = [null => 'No Aplica'] + $this->clasificadores->getAsList();
        $monedas = [null => 'Seleccione Moneda'] + $this->materiales->getListaMonedas();
        return view('articulos.create', ["monedas"=>$monedas])
            ->withClasificadores($clasificadores)
            ->withUnidades($unidades)
            ->withFamilias($familias)
            ->withMonedas($monedas)
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateArticuloRequest $request
     * @param Factory $materiales
     * @return Response
     */
    public function store(CreateArticuloRequest $request, Factory $materiales)
    {
        if ($request->has('nueva_unidad')) {
            $unidad = Unidad::creaUnidad($request->get('nueva_unidad'), $request->get('nueva_unidad'));
        } else {
            $unidad = Unidad::where('unidad', $request->get('unidad'))->firstOrFail();
        }

        $familia = Familia::findOrFail($request->get('familia'));
        $material = $materiales->make(
            $request->get('descripcion'),
            $request->get('descripcion_larga'),
            $request->get('numero_parte'),
            $unidad,
            $unidad
        );

        /*
         * $request->color,
            $request->get('precio_estimado'), 
            $request->precio_proyecto_comparativo, 
            $request->id_moneda, 
            $request->id_moneda_proyecto_comparativo
         */
        $material->color = $request->color;
        if($request->precio_estimado>0){
            $material->precio_estimado = $request->precio_estimado;
        }
        if($request->precio_proyecto_comparativo>0){
            $material->precio_proyecto_comparativo = $request->precio_proyecto_comparativo;
        }
        if($request->id_moneda>0){
            $material->id_moneda = $request->id_moneda;
        }
        if($request->id_moneda_proyecto_comparativo>0){
            $material->id_moneda_proyecto_comparativo = $request->id_moneda_proyecto_comparativo;
        }
        
        if ($request->has('clasificador')) {
            $clasificador = $this->clasificadores->getById($request->get('clasificador'));
            $material->asignaClasificador($clasificador);
        }

        if ($request->hasFile('ficha_tecnica')) {
            $material->agregaFichaTecnica($request->file('ficha_tecnica'));
        }
        $material->control_equipamiento = 1;
        $material->agregarEnFamilia($familia);
        $material->save();

        Flash::success('El articulo fue agregado');

        return redirect()->route('articulos.edit', [$material]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $material = $this->materiales->getById($id);
        $unidades = $this->materiales->getListaUnidades();
        $areas_reporte = $this->materiales->getListaAreasReporte();
        $materiales_secrets = $this->materiales->getListaMaterialesSecrets();
        $familias = $this->materiales->getListaFamilias($material->tipo_material->getTipoReal());
        $clasificadores = [null => 'No Aplica'] + $this->clasificadores->getAsList();
        $monedas = [null => 'Seleccione Moneda'] + $this->materiales->getListaMonedas();
        $idobra = $this->getObraEnContexto()->id_obra;
        $ordenes_compra = $this->materiales->getOrdenCompra($idobra, $id);
        //dd($ordenes_compra);
        return view('articulos.edit')
            ->withMaterial($material)
            ->withUnidades($unidades)
            ->withFamilias($familias)
            ->withMonedas($monedas)
            ->withMateriales_secrets($materiales_secrets)
            ->withAreas_reporte($areas_reporte)
            ->with("idobra",$idobra)
            ->with("ordenes_compra",$ordenes_compra)
            ->withClasificadores($clasificadores);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateArticuloRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateArticuloRequest $request, $id)
    {
        
        $material = $this->materiales->getById($id);
        $unidad = Unidad::where('unidad', $request->get('unidad'))->firstOrFail();
        $clasificador = Clasificador::find($request->get('id_clasificador'));
        
        $id_area_reporte = $request->id_area_reporte;
        $id_material_secrets = $request->id_material_secrets;
        
        $familia = Familia::findOrFail($request->get('familia'));

        $material->fill($request->all());
        $material->asignaAreaReporte($id_area_reporte);
        $material->asignaMaterialesSecrets($id_material_secrets);
        $material->asignaUnidad($unidad);
        $material->agregarEnFamilia($familia);
        $material->asignaClasificador($clasificador);
        if($request->precio_estimado>0){
            $material->precio_estimado = $request->precio_estimado;
            if($request->id_moneda>0){
                $material->id_moneda = $request->id_moneda;
            }
        }else{
            $material->precio_estimado = null;
            $material->id_moneda = null;
        }
        if($request->precio_proyecto_comparativo>0){
            $material->precio_proyecto_comparativo = $request->precio_proyecto_comparativo;
            if($request->id_moneda_proyecto_comparativo>0){
                $material->id_moneda_proyecto_comparativo = $request->id_moneda_proyecto_comparativo;
            }
        }else{
            $material->precio_proyecto_comparativo = null;
            $material->id_moneda_proyecto_comparativo = null;
        }
        
        
        //dd($request, $request->hasFile('ficha_tecnica'));
        if ($request->hasFile('ficha_tecnica')) {
            $material->agregaFichaTecnica($request->file('ficha_tecnica'));
        }
        $precio_estimado = ($request->precio_estimado == "")? "NULL":$request->precio_estimado;
        $precio_proyecto_comparativo = ($request->precio_proyecto_comparativo == "")? "NULL":$request->precio_proyecto_comparativo;
        $moneda = ($request->id_moneda == "")? "NULL":$request->id_moneda;
        $moneda_proyecto_comparativo = ($request->id_moneda_proyecto_comparativo == "")? "NULL":$request->id_moneda_proyecto_comparativo;
        $material->actualizaPreciosEquipamiento($id,$precio_estimado, $moneda, $precio_proyecto_comparativo, $moneda_proyecto_comparativo);
        
        $this->materiales->save($material);

        Flash::success('Los cambios fueron guardados');

        return back();
    }
    
    public function elimina_ficha($id){
        $material = $this->materiales->getById($id);
        $files = [$material->ficha_tecnica_path];
        $material->ficha_tecnica_nombre = "";
        $material->ficha_tecnica_path = "";
        $material->save();
        


        File::delete($files);

        return back();
    }
    
}
