<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\AreaTipo;
use Ghi\Http\Requests\CreateAreaTipoRequest;
use Ghi\Http\Requests\UpdateAreaTipoRequest;
use Ghi\Equipamiento\Areas\AreasTipo;
use Ghi\Equipamiento\Areas\Area;
use Maatwebsite\Excel\Facades\Excel;


class AreasTipoController extends Controller
{
    /**
     * @tipos TipoAreaRepository
     */
    protected $areas_tipo;
    protected $ids_js_areas_abrir = [];
    protected $ids_js_areas_checked = [];

    /**
     *
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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tipo = null;

        if ($request->has('tipo')) {
            $tipo = $this->areas_tipo->getById($request->get('tipo'));
            $descendientes = $tipo->children()->defaultOrder()->get();
        } else {
            $descendientes = $this->areas_tipo->getNivelesRaiz();
        }

        return view('areas-tipo.index')
            ->withTipo($tipo)
            ->withDescendientes($descendientes);

        return view('areas-tipo.index')->withTipos($tipos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tipos = [null => 'Ninguno'] + $this->areas_tipo->getListaTipos();

        return view('areas-tipo.create')
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAreaTipoRequest $request
     * @return Response
     */
    public function store(CreateAreaTipoRequest $request)
    {
        $parent = AreaTipo::find($request->get('parent_id'));
        $tipo = $this->nuevoTipo($request->all(), $parent);

        Flash::success('El nuevo tipo de area fue agregado.');

        return redirect()->route('tipos.index', $parent ? ['tipo' => $parent->id] : []);
    }

    /**
     * Crea un nuevo tipo de area.
     * 
     * @param  array $data
     * @param  AreaTipo|null $parent
     * @return AreaTipo
     */
    protected function nuevoTipo($data, $parent)
    {
        $tipo = AreaTipo::nuevo($data)
            ->enObra($this->getObraEnContexto())
            ->dentroDe($parent);

        $tipo->save();

        return $tipo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipo = $this->areas_tipo->getById($id);
        $tipos = [null => 'Inicio'] + $this->areas_tipo->getListaTipos();

        return view('areas-tipo.datos-generales')
            ->withTipo($tipo)
            ->withTipos($tipos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAreaTipoRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateAreaTipoRequest $request, $id)
    {
        $tipo = $this->areas_tipo->getById($id);
        $parent = AreaTipo::find($request->get('parent_id'));

        $tipo->fill($request->all());

        if ($request->has('move_up')) {
            $tipo->up();
            return back();
        }

        if ($request->has('move_down')) {
            $tipo->down();
            return back();
        }

        $tipo->dentroDe($parent);
        $this->areas_tipo->save($tipo);

        Flash::success('Los cambios fueron guardados.');

        return back();
    }
    
    public function actualizaAreas(Request $request, $id){
        //dd($request, $id);
        $this->areas_tipo->actualizaAreas($request->areas, $id);
        $tipo = $this->areas_tipo->getById($id);
        return view('areas-tipo.areas-asignadas')
            ->withTipo($tipo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tipo = $this->areas_tipo->getById($id);
        $isRoot = $tipo->isRoot();

        if (! $isRoot) {
            $parent_id = $tipo->parent->id;
        }

        $this->areas_tipo->delete($tipo);

        Flash::message('El tipo de area fue borrado.');

        return redirect()->route('tipos.index', $isRoot ? [] : ['tipo' => $parent_id]);
    }
    
    public function areasJs($id){
        $tipo = $this->areas_tipo->getById($id);
        $this->generaArregloAbiertas($tipo);
        $this->generaArregloSeleccionadas($tipo);
        $areas = Area::whereRaw('parent_id is null and id_obra = ?', [$this->getObraEnContexto()->id_obra])
                ->defaultOrder()->withDepth()->get();
        $i = 0;
        foreach($areas as $area){
            $this->lista_areas[$i] = [
                "id"=>$area->id,
                "text"=>$area->nombre,
            ];
            
            if (in_array($area->id, $this->ids_js_areas_abrir)){
                $this->lista_areas[$i]["state"]["opened"] = true;
            }
            if (in_array($area->id, $this->ids_js_areas_checked)){
                $this->lista_areas[$i]["state"]["selected"] = true;
            }else{
                $this->lista_areas[$i]["state"]["selected"] = false;
            }
            
            $hijos = $this->obtieneHijos($area);
            if ($hijos != null){
                $this->lista_areas[$i]["children"] = $hijos;
            }
            $i++;
        }
        //dd($this->lista_areas);
        return json_encode($this->lista_areas);
    }
    

    
    public function obtieneHijos($area){
        $hijos = $area->areas_hijas()->defaultOrder()->withDepth()->get();
        $regresa = null;
        $i = 0;
        foreach($hijos as $hijo){
            $regresa[$i] = [
                "id"=>$hijo->id,
                "text"=>$hijo->nombre,
            ];
            if (in_array($hijo->id, $this->ids_js_areas_abrir)){
                $regresa[$i]["state"]["opened"] = true;
            }
            if (in_array($hijo->id, $this->ids_js_areas_checked)){
                $regresa[$i]["state"]["selected"] = true;
                //dd("entro",$hijo->id, $this->ids_js_areas_checked, $regresa[$i]["state"]);
            }else{
                $regresa[$i]["state"]["selected"] = false;
            }
            if($hijo->areas_hijas){
                $des = $this->obtieneHijos($hijo);
                if($des != null){
                    $regresa[$i]["children"] = $des;
                }
            }
            $i++;
        }
        return $regresa;
    }
    private function generaArregloAbiertas(AreaTipo $tipo){
        #Obtener áreas hijas
        $areas = $tipo->areas;
        foreach($areas as $area){
            $this->ids_js_areas_abrir[] = $area->id;
            foreach ($area->getAncestors() as $area_an) {
                $this->ids_js_areas_abrir[] = $area_an->id;
            }
        }
        $this->ids_js_areas_abrir = array_unique($this->ids_js_areas_abrir);
    }
    private function generaArregloSeleccionadas(AreaTipo $tipo){
        #Obtener áreas hijas
        $areas = $tipo->areas;
        foreach($areas as $area){
            $this->ids_js_areas_checked[] = $area->id;
        }
        //dd($this->ids_js_areas_checked);
        $this->ids_js_areas_checked = array_unique($this->ids_js_areas_checked);
    }
    
    public function articulosRequeridosXLS($id){
        $area_tipo = AreaTipo::findOrFail($id);
        Excel::create('AreaTipoArticulosRequerido'.date("Ymd_his"), function($excel) use($area_tipo) {

            $excel->sheet($area_tipo->nombre, function($sheet) use($area_tipo) {
                $arreglo = AreaTipo::arregloArticulosRequeridosXLS($area_tipo->id);
                $sheet->fromArray($arreglo);
                $sheet->row(1, function($row){
                    $row->setBackground('#cccccc');
                });
                $sheet->freezeFirstRow();
                
                $sheet->setAutoFilter();
            });
            
        })->export('xlsx');
    }
}
