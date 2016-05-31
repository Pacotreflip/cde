<?php

use Ghidev\Fpdf\Rotation;

class PDF extends Rotation {
    
    var $entrega;
    var $numItems;
    var $WidthTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = "";
    
    function __construct($p,$cm,$Letter, $entrega) {
        
        parent::__construct($p,$cm,$Letter);
        $this->SetAutoPageBreak(true,4.5);
        $this->entrega = $entrega;
        $this->WidthTotal = $this->GetPageWidth() - 2;
        $this->numItems = count($this->entrega->partida_articulos());
        $this->txtTitleTam = 18;
        $this->txtSubtitleTam = 13;
        $this->txtSeccionTam = 9;
        $this->txtContenidoTam = 7;
        $this->txtFooterTam = 6;
    }
    
    function header() {
        $this->encabezados();
        $this->logo();
        
        $y_inicial = $this->getY();
        $x_inicial = $this->getX();
        $this->setY($y_inicial);
        $this->setX($x_inicial);

        $y_final = $this->entrega->concepto ? $this->concepto() : $this->gety();
        $this->setY($y_inicial);
//        $alto = abs($y_final - $y_inicial);
//        $this->entrega->concepto ? $this->Rect($x_inicial, $y_inicial, 0.5 * $this->WidthTotal, $alto) : 0;
        $this->setY($y_final);
        $this->Ln(0.5);
        
        if($this->encola == "items") {
            $this->SetWidths(array(0));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('1,1,1'));
            $this->SetRounds(array('0'));
            $this->SetRadius(array(0));
            $this->SetHeights(array(0));
            $this->Row(Array(''));
            $this->SetFont('Arial', 'B', $this->txtSeccionTam);
            $this->SetTextColors(array('255,255,255'));
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ENTREGADOS'), 0, 1, 'L');     
            
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.55 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.17 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'FD', 'DF', 'DF', 'FD'));
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.55 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.17 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', utf8_decode("Familia"), utf8_decode("Descripción"), utf8_decode("Unidad"), utf8_decode("Cantidad Entregada"), utf8_decode("Ubicación")));
            
            $this->SetRounds(array('', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'R', 'L'));
            
        }
        else if($this->encola == "observaciones") {
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
    }
    
    function encabezados (){
        
        
        // Título
        $this->SetFont('Arial', 'B', $this->txtTitleTam - 3);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode($this->entrega->obra->descripcion), 0, 1, 'L', 0);
        
        $this->SetFont('Arial', '', $this->txtSubtitleTam -1);
        $this->CellFitScale(0.6 * $this->WidthTotal, 0.35, utf8_decode('Entrega de Áreas o Materiales al Cliente - # '.$this->entrega->numero_folio), 0, 1, 'L', 0);
        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);

        //Concepto (Titulo)
        if($this->entrega->concepto) {
            $this->SetFont('Arial', 'B', $this->txtSeccionTam);
            $this->Cell(0.5 * $this->WidthTotal, 0.7, utf8_decode('Concepto'), 0, 0, 'L');
        }
        
        $this->SetFont('Arial', '', $this->txtFooterTam);
        $this->Cell($this->entrega->concepto ? 0.5 * $this->WidthTotal : $this->WidthTotal, 0.7, utf8_decode('Fecha: ' . $this->entrega->fecha_entrega->format('d/m/Y')), 0, 1, 'R'); 

    }
    
    function concepto() {
        
//        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
//        $this->Cell(0.2 * $this->WidthTotal, 0.5, utf8_decode('Fecha de Entrega:'), '', 0, 'L');
//        $this->SetFont('Arial', '', $this->txtContenidoTam);
//        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->entrega->fecha_entrega->format('Y-m-d h:m A')), '', 1, 'L');
//        
//        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
//        $this->Cell(0.2 * $this->WidthTotal, 0.5, utf8_decode('Persona que Entrega:'), '', 0, 'LB');
//        $this->SetFont('Arial', '', $this->txtContenidoTam);
//        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->entrega->entrega), '', 1, 'L');
//        
//        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
//        $this->Cell(0.2 * $this->WidthTotal, 0.5, utf8_decode('Persona que Recibe:'), '', 0, 'LB');
//        $this->SetFont('Arial', '', $this->txtContenidoTam);
//        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->entrega->recibe), '', 1, 'L');
//                
//        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
//        $this->Cell(0.2 * $this->WidthTotal, 0.5, utf8_decode('Persona que Registro Entrega:'), '', 0, 'LB');
//        $this->SetFont('Arial', '', $this->txtContenidoTam);
//        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->entrega->usuario->present()->nombreCompleto), '', 1, 'L');
//  
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->Write(0.4, utf8_decode($this->entrega->concepto));
        
        return $this->GetY() + 0.4;
    }
    
    function items() {        
        if($this->numItems > 0) {
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
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ENTREGADOS'), 0, 1, 'L');
            
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.55 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.17 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'FD', 'DF', 'DF', 'FD'));
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.55 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.17 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', utf8_decode("Familia"), utf8_decode("Descripción"), utf8_decode("Unidad"), utf8_decode("Cantidad Entregada"), utf8_decode("Ubicación")));
            
            foreach($this->entrega->partida_articulos() as $articulo) {
                $this->SetFont('Arial', '', 6);
                $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.55 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.17 * $this->WidthTotal));
                $this->encola="items";
                $this->SetRounds(array('', '', '', '', ''));
                $this->SetRadius(array(0, 0, 0, 0, 0, 0));
                $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetHeights(array(0.35));
                $this->SetAligns(array('C', 'L', 'L', 'L', 'R', 'L'));

                if ($i == $this->numItems) {
                    $this->SetRounds(array('4', '', '', '', '', '3'));
                    $this->SetRadius(array(0.2, 0, 0, 0, 0, 0.2));
                }

                $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.55 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.17 * $this->WidthTotal));
                $this->encola = "items";
//                        for($cont = 0; $cont < 50; $cont ++){
                $this->Row(array($i, utf8_decode($articulo["familia"]), utf8_decode($articulo["descripcion"]), utf8_decode($articulo["unidad"]), $articulo["cantidad_asignada"], utf8_decode($articulo["ubicacion_asignada"])));
//                        }
                $i++;   
            }
            $this->encola = "";
        }
        else {
            $this->CellFitScale(19.5, 1, utf8_decode('NO HAY ARTÍCULOS POR MOSTRAR'), 1, 0, 'C');
            $this->Ln(1);
        }  
    }
    
    function firma(){
        $this->SetY(-4);  
        $this->SetFont('Arial', '', 6);
        $this->SetFillColor(180, 180, 180);
        
        $this->SetX(0.1 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 0.4, utf8_decode('ENTREGA'), 'TRLB', 0, 'C', 1);
        $this->SetX(0.375 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 0.4, utf8_decode('AUTORIZA'), 'TRLB', 0, 'C', 1);
        $this->SetX(0.650 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 0.4, utf8_decode('RECIBE'), 'TRLB', 1, 'C', 1);

        $this->SetX(0.1 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 1.5, '', 'RLB', 0, 'C');
        $this->SetX(0.375 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 1.5, '', 'RLB', 0, 'C');
        $this->SetX(0.650 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 1.5, '', 'RLB', 1, 'C');
        
        $this->SetX(0.1 * $this->GetPageWidth());
        $this->CellFitScale(0.25 * $this->GetPageWidth(), 0.4, utf8_decode(trim($this->entrega->entrega)), 'TRLB', 0, 'C', 1);
        $this->SetX(0.375 * $this->GetPageWidth());
        $this->CellFitScale(0.25 * $this->GetPageWidth(), 0.4, '', 'TRLB', 0, 'C', 1);
        $this->SetX(0.650 * $this->GetPageWidth());
        $this->CellFitScale(0.25 * $this->GetPageWidth(), 0.4, utf8_decode(trim($this->entrega->recibe)), 'TRLB', 1, 'C', 1);
    }
    
    function logo(){
        $this->image(public_path('img/logo_hc.png'), $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);       
    }
    
    function observaciones() {
        $this->elcola = "";
        
        if($this->entrega->observaciones){
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
            $this->Row(array(utf8_decode($this->entrega->observaciones)));       
        }  
    } 
        
    function Footer() {
        
        $this->firma();
        
        $this->SetY(-1.25);
        $this->SetFont('Arial', '', $this->txtFooterTam);
        $this->SetY(-1);
        $this->SetFont('Arial', 'B', $this->txtFooterTam);
        $this->Cell(0.5 * $this->WidthTotal, .4, utf8_decode('Formato generado desde el módulo de Control de Equipamiento.'), 0, 0, 'L');
        $this->Cell(0.5 * $this->WidthTotal, .4, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->SetY(- 1.25);
        $this->SetFont('Arial', '', $this->txtFooterTam);  
        $this->Cell(6.5, .4, utf8_decode('Fecha de Consulta: ' . date('Y-m-d g:i a') . '    Registró: ' . $this->entrega->usuario->present()->nombreCompleto), 0, 0, 'L');
    }      
}

$pdf = new PDF('p', 'cm', 'Letter', $entrega);
$pdf->SetMargins(1, 0.5, 1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->items();
$pdf->Ln(0.5);
$pdf->observaciones();
$pdf->Output('I', 'Entrega_'.$entrega->numero_folio.'.pdf', 1);
exit; 