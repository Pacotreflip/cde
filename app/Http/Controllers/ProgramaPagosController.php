<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as R;
use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Maatwebsite\Excel\Facades\Excel;

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
                ->orderBy('empresas.razon_social')
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
                ->join("monedas", "monedas.id_moneda", "=", "transacciones.id_moneda")
                ->whereRaw("equipamiento = 1 and Equipamiento.pagos_programados.fecha between '{$fecha_inicial} 00:00:00' and '{$fecha_final} 23:59:59' and empresas.razon_social LIKE '%{$proveedor}%' ")
                ->select(DB::raw("transacciones.id_transaccion, transacciones.monto as monto, monedas.abreviatura as moneda, dbo.zerofill(4,transacciones.numero_folio) as folio_oc, empresas.razon_social"))
                ->groupBy(DB::raw("transacciones.id_transaccion, transacciones.monto, monedas.abreviatura, dbo.zerofill(4,transacciones.numero_folio), empresas.razon_social"))
                ->orderBy("empresas.razon_social")
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
        if($request->xls == 1) {
            $this->xls($proveedor, $proveedores, $compras, $anios, $meses, $dias, $fecha_inicial, $fecha_final)->download('xlsx');
        }
 
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

    public function xls($proveedor, $proveedores, $compras, $anios, $meses, $dias, $fecha_inicial, $fecha_final) { 
        
        $data = [
            'proveedor' => $proveedor, 
            'proveedores' => $proveedores, 
            'compras' => $compras, 
            'anios' => $anios, 
            'meses' => $meses, 
            'dias' => $dias,
            'i' => 1,
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
                ];
        
        return Excel::create('Programa de pagos '.$proveedor, function($excel) use ($data){
            $excel->sheet('Pagos Programados', function($sheet) use ($data) {
                
                $montosStyle = array(
                    'alignment' => array(
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ),
                    'numberformat' => array(
                        'code' => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
                    )
                );
                
                $encabezadosStyle = array(
                    'alignment' => array(
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ),
                    'font' => array(
                        'bold' => true,
                    ),
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E0E0E0' )
                    )
                );
                
                $tituloStyle = array(
                    'font' => array(
                        'size' => 16,
                        'bold' => true
                    )
                );
                
                $sheet->mergeCells('B2:O2');
                $sheet->setCellValue('B2', 'PROGRAMA DE PAGOS '.($data['proveedor'] != '' ? '- '.$data['proveedor'] : ''));
                $sheet->setCellValue('B3', 'Del:');
                $sheet->setCellValue('C3', $data['fecha_inicial']);
                $sheet->setCellValue('B4', 'Al:');
                $sheet->setCellValue('C4', $data['fecha_final']);
                $sheet->getStyle('B2')->applyFromArray($tituloStyle);
                $col_inicial = 1;
                $row_inicial = 6;
                $inicial = 6;
                $sheet->mergeCells('B'.(($inicial)).':B'.($inicial+2));
                $sheet->setCellValue('B'.(($inicial)), '#');
                $sheet->mergeCells('C'.(($inicial)).':J'.($inicial+2));
                $sheet->setCellValue('C'.($inicial), 'Proveedor');
                $sheet->mergeCells('K'.($inicial).':L'.($inicial+2));
                $sheet->setCellValue('K'.($inicial), 'Orden de Compra');
                
                $column = 12;
                foreach($data['anios'] as $anio) {
                    $sheet->setCellValueByColumnAndRow($column, $inicial, $anio->anio);
                    $sheet->mergeCells($this->cellsToMergeByColsRow($column, $column+$anio->cantidad_dias - 1,$inicial, $inicial));
                    $column += $anio->cantidad_dias;
                }
                
                $column = 12;
                $inicial++;
                foreach($data['meses'] as $mes) {
                    $sheet->setCellValueByColumnAndRow($column, $inicial, $mes->mesdes);
                    $sheet->mergeCells($this->cellsToMergeByColsRow($column, $column+$mes->cantidad_dias - 1,$inicial, $inicial));
                    $column += $mes->cantidad_dias;
                }
                
                $column = 12;
                $inicial++;
                foreach($data['dias'] as $dia) {
                    $sheet->setCellValueByColumnAndRow($column, $inicial, $dia->dia);
                    $column ++;                    
                }
                
                $inicial++;
                $i = $data['i'];
                foreach($data['compras'] as $compra) {
                    $column = 12;
                    $sheet->mergeCells($this->cellsToMergeByColsRow(2, 9, $inicial, $inicial));
                    $sheet->mergeCells($this->cellsToMergeByColsRow(10, 11, $inicial, $inicial));
                    $sheet->setCellValueByColumnAndRow(1, $inicial, $i);
                    $sheet->setCellValueByColumnAndRow(2, $inicial, $compra->razon_social);
                    $sheet->setCellValueByColumnAndRow(10, $inicial, '# '.$compra->folio_oc);

                    foreach($data['dias'] as $dia) {
                        if(array_key_exists($dia->anio_mes_dia, $compra->anio_mes_dia_pago)) {
                            $sheet->setCellValueByColumnAndRow($column, $inicial, number_format($compra->anio_mes_dia_pago[$dia->anio_mes_dia]["monto"], 2, '.', ',').' '.$compra->moneda);
                            $column++;
                        } else {
                            $column++;
                        }                   
                    }
                    
                    $inicial++;
                    $i++;
                }

                $col_final = $column-1;
                $row_final = $inicial-1;
                
                $sheet->getStyle($this->cellsToMergeByColsRow($col_inicial + 11, $col_final, $row_inicial+3, $row_final))
                        ->applyFromArray($montosStyle);                     
                $sheet->getStyle($this->cellsToMergeByColsRow($col_inicial, $col_final, $row_inicial, $row_inicial+2))
                        ->applyFromArray($encabezadosStyle);                     


                $sheet->setBorder($this->cellsToMergeByColsRow($col_inicial, $col_final, $row_inicial, $row_final), 'thin');
            });
        });
    }
        
    public function cellsToMergeByColsRow($start = -1, $end = -1, $row = -1, $row2 = -1){
        $merge = 'A1:A1';
        if($start>=0 && $end>=0 && $row>=0 && $row2>=0){
            $start = \PHPExcel_Cell::stringFromColumnIndex($start);
            $end = \PHPExcel_Cell::stringFromColumnIndex($end);
            $merge = "$start{$row}:$end{$row2}";
        }
        return $merge;
    }
}
