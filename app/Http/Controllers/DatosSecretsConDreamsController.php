<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\ReporteCostos\DatosSecretsConDreams;
use Ghi\Equipamiento\Articulos\Materiales;
use Ghi\Equipamiento\ReporteCostos\AreaSecrets;
use Ghi\Equipamiento\ReporteCostos\AreaDreams;
use Ghi\Equipamiento\ReporteCostos\AreaReporte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class DatosSecretsConDreamsController extends Controller
{
    protected $materiales;
    
    public function __construct(Materiales $materiales) {
        $this->middleware('auth');
        $this->middleware('context');
        
        $this->materiales = $materiales;
        parent::__construct();
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('datosSecretsConDreams.index')
                ->with('datosSecretsConDreams', DatosSecretsConDreams::paginate(100));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $familias = $this->materiales->getListaFamilias(\Ghi\Equipamiento\Articulos\TipoMaterial::TIPO_MATERIALES);
        $areas_secrets = AreaSecrets::all()->lists('area_secrets', 'id');
        $areas_dreams = AreaDreams::all()->lists('area_dreams', 'id');
        $areas_reporte = AreaReporte::all()->lists('area_reporte', 'id');
        $tipos = DB::connection("cadeco")
                ->table("Equipamiento.material_clasificadores")
                ->select("id", "nombre")
                ->lists("nombre", "id");
        $monedas = DB::connection("cadeco")
                ->table("dbo.monedas")
                ->select("id_moneda", "nombre")
                ->lists("nombre", "id_moneda");
        
        return view ('datosSecretsConDreams.create')
                ->with('familias', $familias)
                ->with('areas_secrets', $areas_secrets)
                ->with('areas_dreams', $areas_dreams)
                ->with('areas_reporte', $areas_reporte)
                ->with('tipos', $tipos)
                ->with('monedas', $monedas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DatosSecretsConDreams $datosSecretsConCreams)
    {
        dd($datosSecretsConCreams);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
