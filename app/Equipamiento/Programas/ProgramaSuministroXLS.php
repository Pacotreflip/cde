<?php

namespace Ghi\Equipamiento\Programas;

use Maatwebsite\Excel\Facades\Excel;

class ProgramaSuministroXLS {
    //put your code here
    
    protected $excel;
    protected $data;
    
    public function __construct($data) {
        $this->data = $data;
        $this->excel = Excel::create('ProgramaSuministro');
    }
    
    public function download() {
        $data = $this->data;
        
        $this->excel->sheet('Programa de Suministro', function($sheet) use ($data) {

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
            
            $info_style = [
                'font' => [
                    'bold' => true,
                    'size' => 9
                ],
                
            ];
            
            $fecha_rebasada_style = [
                'alignment' => [
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FF0000']
                ]
            ];
            
            $fecha_no_rebasada_style = [
                'alignment' => [
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => '00CCCC']
                ]
            ];
            
            $suministro_completado_style = [
                'alignment' => [
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => '00CC66']
                ]
            ];

            $sheet->mergeCells('B2:L2');
            $sheet->setCellValue('B2', 'PROGRAMA DE SUMINISTRO '.($data['proveedor'] != '' ? '- '.$data['proveedor'].' ' : ' ').'( '.($data['fecha_inicial'].' - '.$data['fecha_final'].' )'));
            $sheet->getStyle('B2')->applyFromArray($tituloStyle);
            $col_inicial = 1;
            $row_inicial = 10;
            $inicial = 10;
            $sheet->mergeCells('B'.(($inicial)).':B'.($inicial+2));
            $sheet->setCellValue('B'.(($inicial)), '#');
            $sheet->mergeCells('C'.(($inicial)).':D'.($inicial+2));
            $sheet->setCellValue('C'.($inicial), 'Proveedor');
            $sheet->mergeCells('E'.($inicial).':O'.($inicial+2));
            $sheet->setCellValue('E'.($inicial), 'Material');
            $sheet->mergeCells('P'.($inicial).':Q'.($inicial+2));
            $sheet->setCellValue('P'.($inicial), 'Orden de Compra');
            
            $sheet->setBorder('B4:B8', 'thin');
            $sheet->setCellValue('B5', '%');
            $sheet->setCellValue('B7', '%');
            $sheet->getStyle('B4')->applyFromArray($fecha_rebasada_style);                            
            $sheet->getStyle('B5')->applyFromArray($fecha_rebasada_style);                            
            $sheet->getStyle('B6')->applyFromArray($fecha_no_rebasada_style);                            
            $sheet->getStyle('B7')->applyFromArray($fecha_no_rebasada_style);                            
            $sheet->getStyle('B8')->applyFromArray($suministro_completado_style); 
            $sheet->getStyle('C4:C8')->applyFromArray($info_style);                            
            $sheet->setCellValue('C4', 'No se ha recibido ningún artículo y la fecha esperada de entrega ha sido rebasada');
            $sheet->setCellValue('C5', 'Se han recibido algunos artículos y la fecha esperada de entrega ha sido rebasada');
            $sheet->setCellValue('C6', 'No se ha recibido ningún artículo y la fecha esperada de entrega no ha sido rebasada');
            $sheet->setCellValue('C7', 'Se han recibido algunos artículos y la fecha esperada de entrega no ha sido rebasada');
            $sheet->setCellValue('C8', 'Suministrado Completamente (100%)');
            
            $column = 17;
            foreach($data['anios'] as $anio) {
                $sheet->setCellValueByColumnAndRow($column, $inicial, $anio->anio);
                $sheet->mergeCells($this->cellsToMergeByColsRow($column, $column+$anio->cantidad_dias - 1,$inicial, $inicial));
                $column += $anio->cantidad_dias;
            }

            $column = 17;
            $inicial++;
            foreach($data['meses'] as $mes) {
                $sheet->setCellValueByColumnAndRow($column, $inicial, $mes->mesdes);
                $sheet->mergeCells($this->cellsToMergeByColsRow($column, $column+$mes->cantidad_dias - 1,$inicial, $inicial));
                $column += $mes->cantidad_dias;
            }

            $column = 17;
            $inicial++;
            foreach($data['dias'] as $dia) {
                $sheet->setCellValueByColumnAndRow($column, $inicial, $dia->dia);
                $column ++;                    
            }

            $inicial++;
            $i = $data['i'];

            foreach($data['materiales'] as $material) {
                $column = 17;
                $sheet->mergeCells($this->cellsToMergeByColsRow(2, 3, $inicial, $inicial));
                $sheet->mergeCells($this->cellsToMergeByColsRow(4, 14, $inicial, $inicial));
                $sheet->mergeCells($this->cellsToMergeByColsRow(15, 16, $inicial, $inicial));
                $sheet->setCellValueByColumnAndRow(1, $inicial, $i);
                $sheet->setCellValueByColumnAndRow(2, $inicial, $material->proveedor);
                $sheet->setCellValueByColumnAndRow(4, $inicial, $material->descripcion);
                $sheet->setCellValueByColumnAndRow(15, $inicial, '# '.$material->folio_oc);

                foreach($data['dias'] as $dia) {
                    if(array_key_exists($dia->anio_mes_dia, $material->anio_mes_dia_suministro($material->folio_oc)) ) {
                        if($data['hoy']->format("Ymd") >= $dia->anio_mes_dia && $material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"] < 100) {
                            if($material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"] > 0) {
                                $sheet->setCellValueByColumnAndRow($column, $inicial, $material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]);
                            }
                            $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($column).$inicial)->applyFromArray($fecha_rebasada_style);                            
                        } else if($material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]== 100) {
                            $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($column).$inicial)->applyFromArray($suministro_completado_style);
                        } else if($data['hoy']->format("Ymd")<$dia->anio_mes_dia) {
                            if($material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]>0)  {
                                $sheet->setCellValueByColumnAndRow($column, $inicial, $material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]);
                            }
                            $sheet->getStyle(\PHPExcel_Cell::stringFromColumnIndex($column).$inicial)->applyFromArray($fecha_no_rebasada_style);
                        }
                    }
                    $column++;
                }

                $inicial++;
                $i++;
            }
            
            $col_final = $column-1;
            $row_final = $inicial-1;
            
            $sheet->getStyle($this->cellsToMergeByColsRow($col_inicial, $col_final, $row_inicial, $row_inicial+2))
                    ->applyFromArray($encabezadosStyle);                     

            $sheet->setBorder($this->cellsToMergeByColsRow($col_inicial, $col_final, $row_inicial, $row_final), 'thin');
            
            
        })->export('xlsx');
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
