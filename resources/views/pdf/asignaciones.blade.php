<?php

use Ghidev\Fpdf\Rotation;

class PDF extends Rotation {
    
    var $asignacion;
    var $WidthTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = "";
    
    function __construct($p,$cm,$Letter, $asignacion) {
        
        parent::__construct($p,$cm,$Letter);
        $this->SetAutoPageBreak(true,4.5);
        $this->asignacion = $asignacion;
        $this->WidthTotal = $this->GetPageWidth() - 2;
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
        $this->detallesAsignacion();

        //Posiciones despues de la primera tabla
        $y_final = $this->getY();
        $this->setY($y_inicial);
         
        $alto = abs($y_final - $y_inicial);
        
        //Redondear Bordes Detalles Asignacion
        $this->SetWidths(array(0.55 * $this->WidthTotal));
        $this->SetRounds(array('1234'));
        $this->SetRadius(array(0.2));
        $this->SetFills(array('255,255,255'));
        $this->SetTextColors(array('0,0,0'));
        $this->SetHeights(array($alto));
        $this->SetStyles(array('DF'));
        $this->SetAligns("L");
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->setY($y_inicial);
        $this->Row(array(""));
        
        if($this->asignacion->recepcion){
        
            //Redondear Bordes Referencias
            $this->SetWidths(array(0.425  * $this->WidthTotal));
            $this->SetRounds(array('1234'));
            $this->SetRadius(array(0.2));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('0,0,0'));
            $this->SetHeights(array($alto));
            $this->SetStyles(array('DF'));
            $this->SetAligns("L");
            $this->SetFont('Arial', '', $this->txtContenidoTam);
            $this->setY($y_inicial);
            $this->setX($x_inicial+ 0.55 * $this->WidthTotal + 0.5);
            $this->Row(array(""));

            $this->setY($y_inicial);
            $this->setX(0.35 * $this->WidthTotal + 0.5);
            $this->referencias($x_inicial);
        }
        
        //Tabla Detalles de la Asignacion
        $this->setY($y_inicial);
        $this->setX($x_inicial);      
        $this->detallesAsignacion();
        
        //Obtener Y despues de la tabla
        $this->setY($y_final);
        $this->Ln(0.5);
        
        if($this->encola == "items"){
            $this->SetWidths(array(0));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('1,1,1'));
            $this->SetRounds(array('0'));
            $this->SetRadius(array(0));
            $this->SetHeights(array(0));
            $this->Row(Array(''));
            $this->SetFont('Arial', 'B', $this->txtSeccionTam);
            $this->SetTextColors(array('255,255,255'));
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ASIGNADOS'), 0, 1, 'L');
            
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.13 * $this->WidthTotal, 0.13 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF'));
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.13 * $this->WidthTotal, 0.13 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', 'No. Parte', utf8_decode("Descripción"), "Unidad", "Cantidad Asignada", utf8_decode("Área Origen"), utf8_decode("Área Destino")));

            $this->SetRounds(array('', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'L', 'R', 'L', 'L'));
        } 
        else if ($this->encola == "observaciones") {
            $this->Ln(0.5);
            $this->SetRounds(array('34'));
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
    
    function titulos (){
        
        // Título
        $this->SetFont('Arial', 'B', $this->txtTitleTam - 3);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode($this->asignacion->obra->descripcion), 0, 1, 'L', 0);
        
        $this->SetFont('Arial', '', $this->txtSubtitleTam -1);
        $this->CellFitScale(0.6 * $this->WidthTotal, 0.35, utf8_decode('Asignación de Artículos - #'.$this->asignacion->numero_folio), 0, 1, 'L', 0);
        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);
        
        //Detalles de la Asignación (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.55 * $this->WidthTotal, 0.7, utf8_decode('Detalles de la Asignación'), 0, $this->asignacion->recepcion ? 0 : 1, 'L');
       
        if($this->asignacion->recepcion){
            $this->Cell(0.5);
            $this->Cell(0.425 * $this->WidthTotal, .7, 'Referencias', 0, 1, 'L');
        }
    }
    
    function detallesAsignacion(){
        
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Fecha Asignación:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->asignacion->fecha_asignacion->format('Y-m-d h:m A')), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Persona que Asigna:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->asignacion->usuario_registro->present()->nombreCompleto), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Persona que Valida:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, '', '', 1, 'L');
    }
    
    function referencias($x_inicial){
        
        $this->setX($x_inicial + 0.55 * $this->WidthTotal + 0.5);
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.12 * $this->WidthTotal, 0.5, utf8_decode('No. de Recepción:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.225 * $this->WidthTotal, 0.5, utf8_decode('# ' . $this->asignacion->recepcion->numero_folio), '', 1, 'L');
    }
    
    function items(){
        
        $numItems = $this->asignacion->items->count();
        $i = 1;

        if ($numItems > 0) {
        
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
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ASIGNADOS'), 0, 1, 'L');

            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.13 * $this->WidthTotal, 0.13 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF'));
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.13 * $this->WidthTotal, 0.13 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', 'No. Parte', utf8_decode("Descripción"), "Unidad", "Cantidad Asignada", utf8_decode("Área Origen"), utf8_decode("Área Destino")));

            foreach($this->asignacion->items as $item){
                $this->SetFont('Arial', '', 6);
                $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.13 * $this->WidthTotal, 0.13 * $this->WidthTotal));
                $this->encola="items";
                $this->SetRounds(array('', '', '', '', '', '', ''));
                $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0));
                $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                $this->SetHeights(array(0.35));
                $this->SetAligns(array('C', 'L', 'L', 'L', 'R', 'L', 'L'));

                if ($i == $numItems ) {
                    $this->SetRounds(array('4', '', '', '', '', '', '3'));
                    $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0.2));
                }

                $this->SetWidths(array(0.04 * $this->WidthTotal, 0.08 * $this->WidthTotal, 0.5 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.06 * $this->WidthTotal, 0.13 * $this->WidthTotal, 0.13 * $this->WidthTotal));
                $this->encola = "items";
//                for($cont = 0; $cont < 50; $cont ++){
                $this->Row(array($i, utf8_decode($item->material->numero_parte), utf8_decode($item->material->descripcion), utf8_decode($item->material->unidad), number_format(utf8_decode($item->cantidad_asignada),0,'.',','), utf8_decode($this->OrigDest($item->area_origen)), utf8_decode($this->OrigDest($item->area_destino))));
//                }
                $i++;
            }
            $this->encola = "";
        } else {
            $this->SetWidths(array(0));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('1,1,1'));
            $this->SetRounds(array('0'));
            $this->SetRadius(array(0));
            $this->SetHeights(array(0));
            $this->Row(Array(''));
            $this->SetFont('Arial', 'B', $this->txtSubtitleTam);
            $this->SetTextColors(array('255,255,255'));
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ASIGNADOS'), 0, 1, 'C');
            
            $this->SetFont('Arial', 'B', $this->txtContenidoTam);
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('NO HAY ARTÍCULOS POR MOSTRAR'), 1, 0, 'C');
            $this->Ln(1);
        }
    }
    
    function OrigDest($area){
        
        if ($area){
            return $area->ruta();
        } else {
            return 'N/A';
        }    
    }
    
    function observaciones(){
        
        $this->encola = "";
        
        if($this->asignacion->observaciones){
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
            $this->Row(array(utf8_decode($this->asignacion->observaciones)));       
        }  
    }
    
    function logo(){
        $this->image(public_path('img/logo_hc.png'), $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);       
    }
        
    function firma(){
        $this->SetY(-4);  
        $this->SetFont('Arial', '', 6);
        $this->SetFillColor(180, 180, 180);
        
        $this->SetX(-1-0.25 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 0.4, utf8_decode('ASIGNA'), 'TRLB', 1, 'C', 1);
        $this->SetX(-1-0.25 * $this->GetPageWidth());
        $this->Cell(0.25 * $this->GetPageWidth(), 1.5, '', 'RLB', 1, 'C');
        $this->SetX(-1-0.25 * $this->GetPageWidth());
        $this->CellFitScale(0.25 * $this->GetPageWidth(), 0.4, utf8_decode($this->asignacion->usuario_registro->present()->nombreCompleto), 'TRLB', 1, 'C', 1);
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

$pdf = new PDF('p', 'cm', 'Letter', $asignacion);
$pdf->SetMargins(1, 0.5, 1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->items();
$pdf->Ln(0.5);
$pdf->observaciones();
$pdf->Output('I', 'Asignacion_'.$pdf->asignacion->numero_folio.'.pdf', 1);
exit; 

?>

       