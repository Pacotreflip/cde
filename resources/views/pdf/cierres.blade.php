<?php

use Ghidev\Fpdf\Rotation;

class PDF extends Rotation {
    
    var $cierre, $articulos;
    var $numPartidas, $numArticulos;
    var $WidthTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = "", $areaEncola = null;
    
    function __construct($p,$cm,$Letter, $cierre, $articulos) {
        
        parent::__construct($p,$cm,$Letter);
        $this->SetAutoPageBreak(true,4.5);
        $this->cierre = $cierre;
        $this->articulos = $articulos;
        $this->numPartidas = $this->cierre->partidas->count();
        $this->WidthTotal = $this->GetPageWidth() - 2;
        $this->numArticulos = $this->totalArticulos();
        $this->txtTitleTam = 18;
        $this->txtSubtitleTam = 13;
        $this->txtSeccionTam = 9;
        $this->txtContenidoTam = 7;
        $this->txtFooterTam = 6;
    }
    
    function header() {
        
        $this->titulos();
        $this->logo();
        //Obtener Posiciones despues de los títulos
        $y_inicial = $this->getY();
        $x_inicial = $this->getX();
        $this->setY($y_inicial);
        $this->setX($x_inicial);
        
        //Tabla Detalles de la Asignación
        $this->detallesCierre();

        //Posiciones despues de la primera tabla
        $y_final = $this->getY();
        $this->setY($y_inicial);
         
        $alto = abs($y_final - $y_inicial);
        
        $this->RoundedRect($x_inicial, $y_inicial, 0.55 * $this->WidthTotal, $alto, 0.2);

        //Redondear Bordes Detalles Asignacion
//        $this->SetWidths(array(0.55 * $this->WidthTotal));
//        $this->SetRounds(array('1234'));
//        $this->SetRadius(array(0.2));
//        $this->SetFills(array('255,255,255'));
//        $this->SetTextColors(array('0,0,0'));
//        $this->SetHeights(array($alto));
//        $this->SetStyles(array('DF'));
//        $this->SetAligns("L");
//        $this->SetFont('Arial', '', $this->txtContenidoTam);
//        $this->setY($y_inicial);
//        $this->Row(array(""));
                
        //Obtener Y despues de la tabla
        $this->setY($y_final);
        $this->Ln(0.5);
        
        if($this->encola == "items"){
            
            //Título artículos Asignados
            $this->SetWidths(array($this->WidthTotal));
            $this->SetRounds(array('1234'));
            $this->SetRadius(array(0.3));
            $this->SetFills(array('0,0,0'));
            $this->SetTextColors(array('255,255,255'));
            $this->SetHeights(array(.7));
            $this->SetStyles(array('DF'));
            $this->SetAligns("C");
            $this->SetFont('Arial', '', $this->txtSubtitleTam);
            $this->Row(Array(utf8_decode('Areas Cerradas')));
            $this->Ln(0.5);
      
            $this->SetWidths(array(0.05 * $this->WidthTotal, 0.3 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.15 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'FD', 'DF', 'DF'));
            $this->SetWidths(array(0.05 * $this->WidthTotal, 0.3 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.15 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C'));
            $this->Row(array('#', 'Clave', utf8_decode("No. Parte"), utf8_decode("Asignaciones Validadas")));

            $this->SetRounds(array('', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'R'));
        } 
        else if ($this->encola == "observaciones") {
            $this->Ln(0.5);
            $this->SetAligns(array('J'));
            $this->SetRounds(array('1234'));
            $this->SetRadius(array(0.2));
            $this->SetAligns(array('J'));
            $this->SetStyles(array('DF'));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetFont('Arial', '', 6);
            $this->SetWidths(array($this->WidthTotal));
        } 
        else if ($this->encola == "articulos"){
            
//            //Título artículos Por area
//            $this->SetWidths(array($this->WidthTotal));
//            $this->SetRounds(array('0'));
//            $this->SetRadius(array(0));
//            $this->SetFills(array('0,0,0'));
//            $this->SetTextColors(array('255,255,255'));
//            $this->SetHeights(array(.7));
//            $this->SetStyles(array('DF'));
//            $this->SetAligns("C");
//            $this->SetFont('Arial', 'B', $this->txtSubtitleTam);
//            $this->Row(Array(utf8_decode('Asignaciones')));
            $this->SetWidths(array(0));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('1,1,1'));
            $this->SetRounds(array('0'));
            $this->SetRadius(array(0));
            $this->SetHeights(array(0));
            $this->Row(Array(''));
            $this->SetFont('Arial', 'B', $this->txtSeccionTam);
            $this->SetTextColors(array('255,255,255'));
            $this->CellFitScale(0.7 * $this->WidthTotal, 1, utf8_decode($this->areaEncola), 0, 0, 'L');
            
            $this->Ln(1);
            
            $this->SetWidths(array(0.05 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.65 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.1 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'FD', 'DF', 'DF'));
            $this->SetWidths(array(0.05 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.65 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.1 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', utf8_decode("No. Parte"), utf8_decode("Descripción"), utf8_decode("Unidad"), utf8_decode("Cantidad")));
            
            $this->SetRounds(array('', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'R'));
        }
    }
    
    function detalleArticulosArea(){ 
//         //Título artículos Por area
//                $this->SetWidths(array($this->WidthTotal));
//                $this->SetRounds(array('0'));
//                $this->SetRadius(array(0));
//                $this->SetFills(array('0,0,0'));
//                $this->SetTextColors(array('255,255,255'));
//                $this->SetHeights(array(.7));
//                $this->SetStyles(array('DF'));
//                $this->SetAligns("C");
//                $this->SetFont('Arial', 'B', $this->txtSubtitleTam);
//                $this->Row(Array(utf8_decode('Asignaciones')));
        foreach($this->cierre->partidas as $partida){
             if($this->GetY() > $this->GetPageHeight() - 6){
            $this->AddPage();
        }
            $this->encola = "";
            $this->areaEncola = $partida->area->ruta();
            $num_articulos_x_area = 0;
            $total = 0;
            foreach($this->articulos as $articulo){
                if($articulo->id_area == $partida->id_area){
                    $num_articulos_x_area ++;
                    $total += $articulo->cantidad_asignada;
                }
            }

            if($num_articulos_x_area > 0){
                
                $i = 1;
                        
                $this->SetWidths(array(0));
                $this->SetFills(array('255,255,255'));
                $this->SetTextColors(array('1,1,1'));
                $this->SetRounds(array('0'));
                $this->SetRadius(array(0));
                $this->SetHeights(array(0));
                $this->Row(Array(''));
                $this->SetFont('Arial', 'B', $this->txtSeccionTam);
                $this->SetTextColors(array('255,255,255'));
                $this->CellFitScale(0.7 * $this->WidthTotal, 1, utf8_decode($this->areaEncola), 0, 0, 'L');
                $this->CellFitScale(0.2 * $this->WidthTotal, 1, utf8_decode('Total de Artículos :'), 0, 0, 'R');
                $this->CellFitScale(0.1 * $this->WidthTotal, 1, $total, 0, 1, 'R');


                $this->SetWidths(array(0.05 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.65 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.1 * $this->WidthTotal));
                $this->SetFont('Arial', '', 6);
                $this->SetStyles(array('DF', 'DF', 'FD', 'DF', 'DF'));
                $this->SetWidths(array(0.05 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.65 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.1 * $this->WidthTotal));
                $this->SetRounds(array('1', '', '', '', '2'));
                $this->SetRadius(array(0.2, 0, 0, 0, 0.2));
                $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetHeights(array(0.3));
                $this->SetAligns(array('C', 'C', 'C', 'C', 'C'));
                $this->Row(array('#', utf8_decode("No. Parte"), utf8_decode("Descripción"), utf8_decode("Unidad"), utf8_decode("Cantidad")));
                
                foreach($this->articulos as $articulo){
                    if($articulo->id_area == $partida->id_area){
                        $this->SetFont('Arial', '', 6);
                        $this->SetWidths(array(0.05 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.65 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.1 * $this->WidthTotal));
                        $this->encola="articulos";
                        $this->areaEncola = $partida->area->ruta();
                        $this->SetRounds(array('', '', '', '', ''));
                        $this->SetRadius(array(0, 0, 0, 0, 0));
                        $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                        $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                        $this->SetHeights(array(0.35));
                        $this->SetAligns(array('C', 'L', 'L', 'L', 'R'));

                        if ($i == $num_articulos_x_area) {
                            $this->SetRounds(array('4', '', '', '', '3'));
                            $this->SetRadius(array(0.2, 0, 0, 0, 0.2));
                        }

                        $this->SetWidths(array(0.05 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.65 * $this->WidthTotal, 0.1 * $this->WidthTotal, 0.1 * $this->WidthTotal));
                        $this->encola = "articulos";
//                        for($cont = 0; $cont < 50; $cont ++){
                        $this->Row(array($i, utf8_decode($articulo->numero_parte), utf8_decode($articulo->descripcion), $articulo->unidad, $articulo->cantidad_asignada));
//                        }
                        $i++;
                    }
                }
                $this->encola = "";
            } else {
                $this->CellFitScale(19.5, 1, utf8_decode('NO HAY ARTÍCULOS POR MOSTRAR'), 1, 0, 'C');
                $this->Ln(1);
            }
        }
    }
    
    function titulos (){
        
        // Título
        $this->SetFont('Arial', 'B', $this->txtTitleTam);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode('Cierre de Áreas - # '.$this->cierre->numero_folio), 0, 1, 'L', 0);
        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);
        
        //Detalles de la Asignación (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.55 * $this->WidthTotal, 0.7, utf8_decode('Detalles del Cierre'), 0, 1, 'L');
    }
    
    function detallesCierre(){
        
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('No. Folio:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode('# ' . $this->cierre->numero_folio), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Fecha de Cierre:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->cierre->fecha_cierre->format('Y-m-d h:m A')), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Total de Artículos:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->numArticulos), '', 1, 'L');  
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Persona que Cierra:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->cierre->usuario->present()->nombreCompleto), '', 1, 'L');
    }
    
    function totalArticulos(){
        $total = 0;
        foreach($this->articulos as $articulo){
            $total += $articulo->cantidad_asignada;      
        }
        return $total;
    }
    
//    function items(){
//        
//        $i = 1;
//
//        if ($this->numPartidas > 0) {
//        
//            $i = 1;
//            //Título artículos Asignados
//            $this->SetWidths(array($this->WidthTotal));
//            $this->SetRounds(array('1234'));
//            $this->SetRadius(array(0.3));
//            $this->SetFills(array('0,0,0'));
//            $this->SetTextColors(array('255,255,255'));
//            $this->SetHeights(array(.7));
//            $this->SetStyles(array('DF'));
//            $this->SetAligns("C");
//            $this->SetFont('Arial', '', $this->txtSubtitleTam);
//            $this->Row(Array(utf8_decode('Areas Cerradas')));
//            $this->Ln(0.5);
//            
//            $this->SetWidths(array(0.05 * $this->WidthTotal, 0.3 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.15 * $this->WidthTotal));
//            $this->SetFont('Arial', '', 6);
//            $this->SetStyles(array('DF', 'FD', 'DF', 'DF'));
//            $this->SetWidths(array(0.05 * $this->WidthTotal, 0.3 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.15 * $this->WidthTotal));
//            $this->SetRounds(array('1', '', '', '2'));
//            $this->SetRadius(array(0.2, 0, 0, 0.2));
//            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180'));
//            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0'));
//            $this->SetHeights(array(0.3));
//            $this->SetAligns(array('C', 'C', 'C', 'C'));
//            $this->Row(array('#', 'Clave', utf8_decode("Área"), utf8_decode("Asignaciones Validadas")));
//            foreach($this->cierre->partidas as $partida){
//                $this->SetFont('Arial', '', 6);
//                $this->SetWidths(array(0.05 * $this->WidthTotal, 0.3 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.15 * $this->WidthTotal));
//                $this->encola="items";
//                $this->SetRounds(array('', '', '', ''));
//                $this->SetRadius(array(0, 0, 0, 0));
//                $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255'));
//                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0'));
//                $this->SetHeights(array(0.35));
//                $this->SetAligns(array('C', 'L', 'L', 'R'));
//
//                if ($i == $this->numPartidas ) {
//                    $this->SetRounds(array('4', '', '', '3'));
//                    $this->SetRadius(array(0.2, 0, 0, 0.2));
//                }
//
//                $this->SetWidths(array(0.05 * $this->WidthTotal, 0.3 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.15 * $this->WidthTotal));
//                $this->encola = "items";
//                $this->Row(array($i, utf8_decode($partida->area->clave), utf8_decode($partida->area->ruta),number_format(utf8_decode($partida->area->cantidad_validada()),0,'.',',')));
//
//            $i++;
//            }
//            $this->encola = "";
//        } else {
//            $this->CellFitScale(19.5, 1, utf8_decode('NO HAY ÁREAS POR MOSTRAR'), 1, 0, 'C');
//            $this->Ln(1);
//        }
//    }
    
    function observaciones(){
        
        $this->elcola = "";
        
        if($this->cierre->observaciones){
            if($this->GetY() > $this->GetPageHeight() - 5){
                $this->AddPage();
            }
            $this->SetWidths(array($this->WidthTotal));
            $this->SetRounds(array('12'));
            $this->SetRadius(array(0.2));
            $this->SetFills(array('180,180,180'));
            $this->SetTextColors(array('0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetFont('Arial', '', 6);
            $this->SetAligns(array('C'));
            $this->Row(array("Observaciones"));
            $this->SetRounds(array('34'));
            $this->SetRadius(array(0.2));
            $this->SetAligns(array('J'));
            $this->SetStyles(array('DF'));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetFont('Arial', '', 6);
            $this->SetWidths(array($this->WidthTotal));
            $this->encola = "observaciones";
            $this->Row(array(utf8_decode($this->cierre->observaciones)));       
        }  
    }
    
    function firma(){
        $this->SetY(-4);  
        $this->SetFont('Arial', '', 6);
        $this->SetFillColor(180, 180, 180);
        $this->SetX(0.75 * $this->WidthTotal + 1);
        $this->Cell(0.25 * $this->WidthTotal, 0.4, utf8_decode('CIERRA'), 'TRLB', 1, 'C', 1);
        $this->SetX(0.75 * $this->WidthTotal + 1);
        $this->Cell(0.25 * $this->WidthTotal, 1.5, '', 'RLB', 1, 'C');
        $this->SetX(0.75 * $this->WidthTotal + 1);
        $this->CellFitScale(0.25 * $this->WidthTotal,0.4, $this->cierre->usuario->present()->nombreCompleto, 'TRLB', 0, 'C', 1);
    }
        
    function logo(){
        $this->image(public_path('img/logo_hc.png'), $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);       
    }
    
    function Footer() {
        $this->firma();
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->SetY($this->GetPageHeight() - 1);
        $this->SetFont('Arial', '', $this->txtFooterTam);
        $this->Cell(6.5, .4, utf8_decode('Fecha de Consulta: ' . date('Y-m-d g:i a')), 0, 0, 'L');
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->Cell(6.5, .4, '', 0, 0, 'C');
        $this->Cell(6.5, .4, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->SetY($this->GetPageHeight() - 1.3);
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->Cell(6.5, .4, utf8_decode('Formato generado desde el módulo de Control de Equipamiento.'), 0, 0, 'L');
    }
}

$pdf = new PDF('p', 'cm', 'Letter', $cierre, $articulos);
$pdf->SetMargins(1, 0.5, 1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->detalleArticulosArea();
$pdf->Ln(0.5);
$pdf->observaciones();
$pdf->Output('I', 'Cierre_de_area_'.$pdf->cierre->numero_folio.'.pdf', 1);
exit; 

?>
