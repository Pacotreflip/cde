<?php

use Ghidev\Fpdf\Rotation;

class PDF extends Rotation {
    
    var $modelo;
    var $WeightTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = "";
    
    function __construct($p,$cm,$Letter, $modelo) {
        parent::__construct($p,$cm,$Letter);
        $this->SetAutoPageBreak(true,3);
        $this->modelo = $modelo;
        $this->WeightTotal = $this->GetPageWidth() - 2;
        $this->txtTitleTam = 18;
        $this->txtSubtitleTam = 13;
        $this->txtSeccionTam = 9;
        $this->txtContenidoTam = 7;
        $this->txtFooterTam = 6;
    }
    
    function header() {
        
        $this->titulos();
        
        //Obtener Posiciones despues de los títulos
        $y_inicial = $this->getY();
        $x_inicial = $this->getX();
        $this->setY($y_inicial);
        $this->setX($x_inicial);
        
        //Tabla Detalles de la Recepción
        $this->detallesTransferencia();

        //Posiciones despues de la primera tabla
        $y_final = $this->getY();
        $this->setY($y_inicial);
         
        $alto = abs($y_final - $y_inicial);
        
        //Redondear Bordes Detalles Recepción
        $this->SetWidths(array(0.55 * $this->WeightTotal));
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
        
        //Tabla Detalles de la Recepción
        $this->setY($y_inicial);
        $this->setX($x_inicial);      
        $this->detallesTransferencia();
        
        //Obtener Y despues de las dos tablas
        $this->setY($y_final);
        $this->Ln(1);
        
        //Título artículos recibidos
        $this->SetWidths(array($this->WeightTotal));
        $this->SetRounds(array('1234'));
        $this->SetRadius(array(0.3));
        $this->SetFills(array('0,0,0'));
        $this->SetTextColors(array('255,255,255'));
        $this->SetHeights(array(.7));
        $this->SetStyles(array('DF'));
        $this->SetAligns("C");
        $this->SetFont('Arial', '', $this->txtSubtitleTam);
        $this->Row(Array(utf8_decode('Artículos Recibidos')));
        $this->Ln(0.5);
        
        
        
        if($this->encola == "items"){
           
           $this->SetWidths(array(0.035 * $this->WeightTotal, 0.1 * $this->WeightTotal,0.4 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.15 * $this->WeightTotal,0.15 * $this->WeightTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF','DF', 'DF', 'FD', 'DF', 'DF', 'DF'));
            $this->SetWidths(array(0.035 * $this->WeightTotal, 0.085 * $this->WeightTotal,0.4 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.15 * $this->WeightTotal,0.15 * $this->WeightTotal));
            $this->SetRounds(array('1', '', '', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', "No. Parte", utf8_decode("Descripción"), "Unidad", "Cantidad Transferida", "Origen", "Destino"));
         
            $this->SetRounds(array('', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
        }
    }
    
    function titulos (){
        
        // Título
        $this->SetFont('Arial', 'B', $this->txtTitleTam);
        $this->CellFitScale(0.6 * $this->WeightTotal, 1.5, utf8_decode('Transferencia de Artículos'), 0, 1, 'L', 0);
        $this->Line(1, $this->GetY() + 0.5, $this->WeightTotal + 1, $this->GetY() + 0.5);
        $this->Ln(1);
        
        //Detalles de la Recepción (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.55 * $this->WeightTotal,.7,utf8_decode('Detalle de la Transferencia'),0,1,'L');
    }
    
    function detallesTransferencia(){
        
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WeightTotal, 0.5, utf8_decode('No. Folio:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WeightTotal, 0.5, '#'.utf8_decode($this->modelo->numero_folio), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WeightTotal, 0.5, utf8_decode('Fecha:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WeightTotal, 0.5, utf8_decode($this->modelo->fecha_transferencia->format('d-M-Y h:m')), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WeightTotal, 0.5, utf8_decode('Creada Por:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WeightTotal, 0.5, utf8_decode($this->modelo->creado_por), '', 1, 'L');
    }
    
    function items(){
       
        $numItems = $this->modelo->items->count();
        $i = 1;
                
        $this->SetWidths(array(0.035 * $this->WeightTotal, 0.1 * $this->WeightTotal,0.4 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.15 * $this->WeightTotal,0.15 * $this->WeightTotal));
        $this->SetFont('Arial', '', 6);
        $this->SetStyles(array('DF','DF', 'DF', 'FD', 'DF', 'DF', 'DF'));
        $this->SetWidths(array(0.035 * $this->WeightTotal, 0.085 * $this->WeightTotal,0.4 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.15 * $this->WeightTotal,0.15 * $this->WeightTotal));
        $this->SetRounds(array('1', '', '', '', '', '', '2'));
        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0.2));
        $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
        $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
        $this->SetHeights(array(0.3));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array('#', "No. Parte", utf8_decode("Descripción"), "Unidad", "Cantidad Transferida", "Origen", "Destino"));
         
        foreach($this->modelo->items as $item){
            $this->SetFont('Arial', '', 6);
            $this->SetWidths(array(0.035 * $this->WeightTotal, 0.085 * $this->WeightTotal,0.4 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.15 * $this->WeightTotal,0.15 * $this->WeightTotal));
            $this->encola="items";
            $this->SetRounds(array('', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));

            if ($i == $numItems ) {
                $this->SetRounds(array('4', '', '', '', '', '', '3'));
                $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0.2));
            }

            $this->SetWidths(array(0.035 * $this->WeightTotal, 0.085 * $this->WeightTotal,0.4 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.15 * $this->WeightTotal,0.15 * $this->WeightTotal));
            $this->encola = "items";
            $this->Row(array($i, $item->material->numero_parte, $item->material->descripcion, $item->material->unidad, $item->cantidad_transferida, $this->OrigDest($item->origen),$this->OrigDest($item->destino)));
           
            $i++;
        }
        $this->encola="";

    }
    function OrigDest($area){
        $origen = '';
        
        foreach($area->getAncestors() as $path){ 
            $origen .= $path->nombre . ' / ';
        }
         $origen .= $area->nombre;
        return $origen;
    }
    
    function observaciones(){
        
        $this->Ln(.5);
        $this->SetWidths(array(19.5));
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
        $this->SetWidths(array(19.5));
        $this->encola = "observaciones";
        $this->Row(array('  '.$this->modelo->observaciones));
    }
        
    function Footer() {
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

$pdf = new PDF('p', 'cm', 'Letter', $transferencia);
$pdf->SetMargins(1, 0.5, 1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->items();
$pdf->observaciones();

$pdf->Output();
exit; 

?>

       