<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Transacciones\Transaccion;

class ProgramaPagosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $proveedores = Proveedor::join("transacciones", "empresas.id_empresa", "=", "transacciones.id_empresa")
                ->where("id_obra", "=", $this->getIdObra())
                ->whereRaw("equipamiento = 1 and transacciones.tipo_transaccion = 19 and transacciones.equipamiento= 1")
                ->select(DB::raw("empresas.razon_social, empresas.id_empresa"))
                ->groupBy("empresas.id_empresa", "empresas.razon_social")
                ->get();
        
        $proveedor = isset($request->proveedor) ? $request->proveedor : "";
        
        if($request->has("fecha_inicial") && $request->has("fecha_final")){
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;
        }else{
            $hoy1 = Carbon::now();
            $fecha_inicial = $hoy1->subYear(2)->format("Y-m-d");
            $hoy2 = Carbon::now();
            $fecha_final = $hoy2->addMonth(1)->format("Y-m-d");
        }
        
        $compras = Transaccion::join("Equipamiento.pagos_programados", "transacciones.id_transaccion", "=", "Equipamiento.pagos_programados.id_transaccion")
                ->join("empresas", "transacciones.id_empresa", "=", "empresas.id_empresa")
                ->whereRaw("equipamiento = 1 and Equipamiento.pagos_programados.fecha between '{$fecha_inicial} 00:00:00' and '{$fecha_final} 23:59:59' and empresas.razon_social LIKE '%{$proveedor}%' ")
                ->select(DB::raw("transacciones.id_transaccion, transacciones.monto as monto, dbo.zerofill(4,transacciones.numero_folio) as folio_oc"))
                ->groupBy(DB::raw("transacciones.id_transaccion, transacciones.monto, dbo.zerofill(4,transacciones.numero_folio)"))
                ->get();
                
        $anios = DB::connection("cadeco")->select("select anio, count(*) as cantidad_dias 
from (select 
convert(varchar(4),year(Equipamiento.pagos_programados.fecha)) + 
case when len(month( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),month( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),month(Equipamiento.pagos_programados.fecha)) end
  anio_mes, 
  year(Equipamiento.pagos_programados.fecha) as anio,month(Equipamiento.pagos_programados.fecha) as mes,
  case month(Equipamiento.pagos_programados.fecha)when 1 then 'Enero' when 2 then 'Febrero'
  when 3 then 'Marzo' when 4 then 'Abril' when 5 then 'Mayo' when 6 then 'Junio'
  when 7 then 'Julio' when 8 then 'Agosto' when 9 then 'Septiembre'
  when 10 then 'Octubre' when 11 then 'Noviembre' when 12 then 'Diciembre' end mesdes
  from [Equipamiento].[pagos_programados] join transacciones on ([Equipamiento].[pagos_programados].[id_transaccion] = transacciones.id_transaccion)
 join empresas on (transacciones.id_empresa = empresas.id_empresa)
 where transacciones.equipamiento = 1 and Equipamiento.pagos_programados.fecha between '{$fecha_inicial}  00:00:00' and '{$fecha_final} 23:59:59' and empresas.razon_social LIKE '%{$proveedor}%'
 group by year(Equipamiento.pagos_programados.fecha),month(Equipamiento.pagos_programados.fecha),day(Equipamiento.pagos_programados.fecha)) as tab
group by  anio");
 
        $meses = DB::connection("cadeco")->select("

 select mes, anio_mes, mesdes, mesdescor, count(*) as cantidad_dias
 from(
 
 select 
convert(varchar(4),year( Equipamiento.pagos_programados.fecha)) + 
case when len(month( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),month( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),month( Equipamiento.pagos_programados.fecha)) end
  anio_mes, 
    
  convert(varchar(4),year( Equipamiento.pagos_programados.fecha)) + 
case when len(month( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),month( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),month( Equipamiento.pagos_programados.fecha)) end +
case when len(day( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),day( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),day( Equipamiento.pagos_programados.fecha)) end
  anio_mes_dia, 
    
  year( Equipamiento.pagos_programados.fecha) as anio,month( Equipamiento.pagos_programados.fecha) as mes,
  day( Equipamiento.pagos_programados.fecha) as dia,
  
  case month( Equipamiento.pagos_programados.fecha)when 1 then 'Enero' when 2 then 'Febrero'
  when 3 then 'Marzo' when 4 then 'Abril' when 5 then 'Mayo' when 6 then 'Junio'
  when 7 then 'Julio' when 8 then 'Agosto' when 9 then 'Septiembre'
  when 10 then 'Octubre' when 11 then 'Noviembre' when 12 then 'Diciembre' end mesdes,
  
case month( Equipamiento.pagos_programados.fecha)when 1 then 'Ene' when 2 then 'Feb'
  when 3 then 'Mar' when 4 then 'Abr' when 5 then 'May' when 6 then 'Jun'
  when 7 then 'Jul' when 8 then 'Ago' when 9 then 'Sep'
  when 10 then 'Oct' when 11 then 'Nov' when 12 then 'Dic' end mesdescor
  
 from [Equipamiento].[pagos_programados] join transacciones on ([Equipamiento].[pagos_programados].[id_transaccion] = transacciones.id_transaccion)
 join empresas on (transacciones.id_empresa = empresas.id_empresa)
 where transacciones.equipamiento = 1 and Equipamiento.pagos_programados.fecha between '{$fecha_inicial}  00:00:00' and '{$fecha_final} 23:59:59' and empresas.razon_social LIKE '%{$proveedor}%'
 group by year( Equipamiento.pagos_programados.fecha),month( Equipamiento.pagos_programados.fecha),day( Equipamiento.pagos_programados.fecha)
 ) as tabla
 group by  mes, anio_mes, mesdes, mesdescor
 order by anio_mes;");
        $dias = DB::connection("cadeco")->select("select 
convert(varchar(4),year( Equipamiento.pagos_programados.fecha)) + 
case when len(month( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),month( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),month( Equipamiento.pagos_programados.fecha)) end
  anio_mes,  
  
  convert(varchar(4),year( Equipamiento.pagos_programados.fecha)) + 
case when len(month( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),month( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),month( Equipamiento.pagos_programados.fecha)) end +
case when len(day( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),day( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),day( Equipamiento.pagos_programados.fecha)) end
  anio_mes_dia, 
  year( Equipamiento.pagos_programados.fecha) as anio,month( Equipamiento.pagos_programados.fecha) as mes,

case when len(day( Equipamiento.pagos_programados.fecha))=1 then '0' +convert(varchar(4),day( Equipamiento.pagos_programados.fecha))
else convert(varchar(4),day( Equipamiento.pagos_programados.fecha)) end
  dia, 

  case month( Equipamiento.pagos_programados.fecha)when 1 then 'Enero' when 2 then 'Febrero'
  when 3 then 'Marzo' when 4 then 'Abril' when 5 then 'Mayo' when 6 then 'Junio'
  when 7 then 'Julio' when 8 then 'Agosto' when 9 then 'Septiembre'
  when 10 then 'Octubre' when 11 then 'Noviembre' when 12 then 'Diciembre' end mesdes
 from [Equipamiento].[pagos_programados] join transacciones on([Equipamiento].[pagos_programados].[id_transaccion] = transacciones.id_transaccion)
 join empresas on (transacciones.id_empresa = empresas.id_empresa)
where transacciones.equipamiento = 1 and Equipamiento.pagos_programados.fecha between '{$fecha_inicial}  00:00:00' and '{$fecha_final} 23:59:59' and empresas.razon_social LIKE '%{$proveedor}%'
    group by year( Equipamiento.pagos_programados.fecha),month( Equipamiento.pagos_programados.fecha),day( Equipamiento.pagos_programados.fecha)
");
 
        return view('programa_pagos.index')
                ->with([
                    'fecha_inicial' => $fecha_inicial,
                    'fecha_final' => $fecha_final,
                    'proveedores' => $proveedores,
                    'proveedor' => $proveedor,
                    'compras' => $compras,
                    'anios' => $anios,
                    'meses' => $meses,
                    'dias' => $dias,
                    'i' => 1,
                    'hoy' => Carbon::now(),
                    'id_obra' => $this->getIdObra()
                        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
