<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAlmacen;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAsignacion;
use Ghi\Equipamiento\Articulos\Material;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ProgramaSuministroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Muestra un listado de recepciones generadas.
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request);
        //$recepciones = $this->buscar($request->buscar);
        $hoy = Carbon::now();
        if($request->has("fecha_inicial") && $request->has("fecha_final")){
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;
        }else{
            $hoy1 = Carbon::now();
            $fecha_inicial = $hoy1->subMonth(1)->format("Y-m-d");
            $hoy2 = Carbon::now();
            $fecha_final = $hoy2->addMonth(1)->format("Y-m-d");
        }
        $materiales = Material::join("Equipamiento.materiales_fechas_entrega", "materiales.id_material","=", "Equipamiento.materiales_fechas_entrega.id_material")
            ->join("transacciones", "transacciones.id_transaccion","=", "Equipamiento.materiales_fechas_entrega.id_transaccion_orden_compra")
            ->whereRaw("equipamiento = 1 and fecha_entrega between '{$fecha_inicial}  00:00:00' and '{$fecha_final} 23:59:59' ")->orderBy('fecha_entrega')->get();
        
        $anios = DB::connection("cadeco")->select("select anio, count(*) as cantidad_dias 
from (select 
convert(varchar(4),year( fecha_entrega)) + 
case when len(month( fecha_entrega))=1 then '0' +convert(varchar(4),month( fecha_entrega))
else convert(varchar(4),month( fecha_entrega)) end
  anio_mes, 
  year( fecha_entrega) as anio,month( fecha_entrega) as mes,
  case month( fecha_entrega)when 1 then 'Enero' when 2 then 'Febrero'
  when 3 then 'Marzo' when 4 then 'Abril' when 5 then 'Mayo' when 6 then 'Junio'
  when 7 then 'Julio' when 8 then 'Agosto' when 9 then 'Septiembre'
  when 10 then 'Octubre' when 11 then 'Noviembre' when 12 then 'Diciembre' end mesdes
 from [Equipamiento].[materiales_fechas_entrega] join transacciones
 on(Equipamiento.materiales_fechas_entrega.id_transaccion_orden_compra = transacciones.id_transaccion)
 where transacciones.equipamiento = 1 and fecha_entrega between '{$fecha_inicial}  00:00:00' and '{$fecha_final} 23:59:59' 
 group by year( fecha_entrega),month( fecha_entrega),day( fecha_entrega)) as tab
group by  anio");
        $meses = DB::connection("cadeco")->select("

 select mes, anio_mes, mesdes, mesdescor, count(*) as cantidad_dias
 from(
 
 select 
convert(varchar(4),year( fecha_entrega)) + 
case when len(month( fecha_entrega))=1 then '0' +convert(varchar(4),month( fecha_entrega))
else convert(varchar(4),month( fecha_entrega)) end
  anio_mes, 
    
  convert(varchar(4),year( fecha_entrega)) + 
case when len(month( fecha_entrega))=1 then '0' +convert(varchar(4),month( fecha_entrega))
else convert(varchar(4),month( fecha_entrega)) end +
case when len(day( fecha_entrega))=1 then '0' +convert(varchar(4),day( fecha_entrega))
else convert(varchar(4),day( fecha_entrega)) end
  anio_mes_dia, 
    
  year( fecha_entrega) as anio,month( fecha_entrega) as mes,
  day( fecha_entrega) as dia,
  
  case month( fecha_entrega)when 1 then 'Enero' when 2 then 'Febrero'
  when 3 then 'Marzo' when 4 then 'Abril' when 5 then 'Mayo' when 6 then 'Junio'
  when 7 then 'Julio' when 8 then 'Agosto' when 9 then 'Septiembre'
  when 10 then 'Octubre' when 11 then 'Noviembre' when 12 then 'Diciembre' end mesdes,
  
case month( fecha_entrega)when 1 then 'Ene' when 2 then 'Feb'
  when 3 then 'Mar' when 4 then 'Abr' when 5 then 'May' when 6 then 'Jun'
  when 7 then 'Jul' when 8 then 'Ago' when 9 then 'Sep'
  when 10 then 'Oct' when 11 then 'Nov' when 12 then 'Dic' end mesdescor
  
 from [Equipamiento].[materiales_fechas_entrega] join transacciones
 on(Equipamiento.materiales_fechas_entrega.id_transaccion_orden_compra = transacciones.id_transaccion)
 where transacciones.equipamiento = 1 and fecha_entrega between '{$fecha_inicial}  00:00:00' and '{$fecha_final} 23:59:59'
 group by year( fecha_entrega),month( fecha_entrega),day( fecha_entrega)
 ) as tabla
 group by  mes, anio_mes, mesdes, mesdescor
 order by anio_mes;");
        $dias = DB::connection("cadeco")->select("select 
convert(varchar(4),year( fecha_entrega)) + 
case when len(month( fecha_entrega))=1 then '0' +convert(varchar(4),month( fecha_entrega))
else convert(varchar(4),month( fecha_entrega)) end
  anio_mes, 
  
  
  
  convert(varchar(4),year( fecha_entrega)) + 
case when len(month( fecha_entrega))=1 then '0' +convert(varchar(4),month( fecha_entrega))
else convert(varchar(4),month( fecha_entrega)) end +
case when len(day( fecha_entrega))=1 then '0' +convert(varchar(4),day( fecha_entrega))
else convert(varchar(4),day( fecha_entrega)) end
  anio_mes_dia, 
  year( fecha_entrega) as anio,month( fecha_entrega) as mes,

case when len(day( fecha_entrega))=1 then '0' +convert(varchar(4),day( fecha_entrega))
else convert(varchar(4),day( fecha_entrega)) end
  dia, 

  case month( fecha_entrega)when 1 then 'Enero' when 2 then 'Febrero'
  when 3 then 'Marzo' when 4 then 'Abril' when 5 then 'Mayo' when 6 then 'Junio'
  when 7 then 'Julio' when 8 then 'Agosto' when 9 then 'Septiembre'
  when 10 then 'Octubre' when 11 then 'Noviembre' when 12 then 'Diciembre' end mesdes
 from [Equipamiento].[materiales_fechas_entrega] join transacciones
 on(Equipamiento.materiales_fechas_entrega.id_transaccion_orden_compra = transacciones.id_transaccion)
where transacciones.equipamiento = 1 and fecha_entrega between '{$fecha_inicial}  00:00:00' and '{$fecha_final} 23:59:59' 
    group by year( fecha_entrega),month( fecha_entrega),day( fecha_entrega)
");
        return view('programa_suministro.index')
        ->with("fecha_inicial",$fecha_inicial)
        ->with("fecha_final",$fecha_final)
        ->with("anios",$anios)
        ->with("meses",$meses)
        ->with("dias",$dias)
        ->with("i",1)
        ->with("hoy",$hoy)
        ->with("id_obra",$this->getIdObra())
        ->with("materiales",$materiales);
            //->withRecepciones($recepciones);
    }

    /**
     * Busca recepciones de articulos.
     * 
     * @param string  $busqueda
     * @param integer $howMany
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function buscar($busqueda, $howMany = 15)
    {
        return Recepcion::where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($busqueda) {
                $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('persona_recibio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                    ->orWhereHas('empresa', function ($query) use ($busqueda) {
                        $query->where('razon_social', 'LIKE', '%'.$busqueda.'%');
                    })
                    ->orWhereHas('compra', function ($query) use($busqueda) {
                        $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%');
                    });
            })
            ->orderBy('numero_folio', 'DESC')
            ->paginate($howMany);
    }

    /**
     * Muestra un formulario para crear una recepcion.
     *
     * @param Areas $areas
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Areas $areas)
    {
        $proveedores = Proveedor::soloProveedores()
            ->orderBy('razon_social')
            ->lists('razon_social', 'id_empresa')
            ->all();

        $compras = Transaccion::ordenesCompraMateriales()
            ->where('id_obra', $this->getIdObra())
            ->orderBy('numero_folio', 'DESC')
            ->lists('numero_folio', 'id_transaccion')
            ->all();

        $areas = $areas->getListaAreas();

        return view('recepciones.create')
            ->withProveedores($proveedores)
            ->withCompras($compras)
            ->withAreas($areas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateRecepcionRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Requests\CreateRecepcionRequest $request) {
        if ($request->opcion_recepcion == "asignar") {
            $recepcion_asignacion = (new RecibeArticulosAsignacion($request->all(), $this->getObraEnContexto()))->save();
            if ($request->ajax()) {
                return response()->json(['path' => route('asignaciones.show', $recepcion_asignacion)]);
            }
        } elseif ($request->opcion_recepcion == "almacenar") {
            $recepcion = (new RecibeArticulosAlmacen($request->all(), $this->getObraEnContexto()))->save();
            if ($request->ajax()) {
                return response()->json(['path' => route('recepciones.show', $recepcion)]);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recepcion = Recepcion::findOrFail($id);

        return view('recepciones.show')
            ->withRecepcion($recepcion);
    }
    
    public function destroy(){
        
    }
}
