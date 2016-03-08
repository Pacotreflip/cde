<?php

use Ghidev\Fpdf\Rotation;

class PDF extends Rotation {
    
    var $compra;
    var $WeightTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = "";
    
    function __construct($p,$cm,$Letter, $compra) {
        
        parent::__construct($p,$cm,$Letter);
        $this->SetAutoPageBreak(true,3);
        $this->compra = $compra;
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
        
        //Tabla Detalles de la Compra
        $this->detallesCompra();

        //Posiciones despues de la tabla detalles
        $y_final = $this->getY();
        $this->setY($y_inicial);
         
        $alto = abs($y_final - $y_inicial);
        
        //Redondear Bordes Detalles Compra
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
        
        //Tabla Detalles de la Compra
        $this->setY($y_inicial);
        $this->setX($x_inicial);      
        $this->detallesCompra();
        
        //Obtener Y despues de la tabla
        $this->setY($y_final);
        $this->Ln(1);
        
        //Título artículos Adquiridos
        $this->SetWidths(array($this->WeightTotal));
        $this->SetRounds(array('1234'));
        $this->SetRadius(array(0.3));
        $this->SetFills(array('0,0,0'));
        $this->SetTextColors(array('255,255,255'));
        $this->SetHeights(array(.7));
        $this->SetStyles(array('DF'));
        $this->SetAligns("C");
        $this->SetFont('Arial', '', $this->txtSubtitleTam);
        $this->Row(Array(utf8_decode('Artículos Adquiridos')));
        $this->Ln(0.5);
        
        if($this->encola == "items"){
            $this->SetWidths(array(0.05 * $this->WeightTotal, 0.32 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF', 'DF'));
            $this->SetWidths(array(0.05 * $this->WeightTotal, 0.32 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal));
            $this->SetRounds(array('1', '', '', '', '', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.6));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', utf8_decode("Descripción"), "Unidad", "Adquirido", "Precio", "Importe", "Entrega", "Recibido", "% Recibido"));

            $this->SetRounds(array('', '', '', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        } else if ($this->encola == "observaciones") {
            $this->SetRounds(array('34'));
            $this->SetRadius(array(0.2));
            $this->SetAligns(array('J'));
            $this->SetStyles(array('DF'));
            $this->SetFills(array('255,255,255'));
            $this->SetTextColors(array('0,0,0'));
            $this->SetHeights(array(0.3));
            $this->SetFont('Arial', '', 6);
            $this->SetWidths(array(19.5));           
        }
    }
    
    function titulos (){
        
        // Título
        $this->SetFont('Arial', 'B', $this->txtTitleTam);
        $this->CellFitScale(0.6 * $this->WeightTotal, 1.5, utf8_decode('Compra de Artículos - # ' . $this->compra->numero_folio), 0, 1, 'L', 0);
        $this->Line(1, $this->GetY() + 0.5, $this->WeightTotal + 1, $this->GetY() + 0.5);
        $this->Ln(1);
        
        //Detalles de la Compra (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.55 * $this->WeightTotal,.7,utf8_decode('Detalle de la Compra'),0,1,'L');
    }
    
    function detallesCompra(){
        
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WeightTotal, 0.5, utf8_decode('No. Folio:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WeightTotal, 0.5, utf8_decode($this->compra->numero_folio), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WeightTotal, 0.5, utf8_decode('Fecha:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WeightTotal, 0.5, utf8_decode($this->compra->fecha->format('d-M-Y h:m')), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WeightTotal, 0.5, utf8_decode('Proveedor:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WeightTotal, 0.5, utf8_decode($this->compra->empresa->razon_social), '', 1, 'L');
    }
    
    function items(){
        
        $numItems = 0;
        foreach($this->compra->items as $item){
            foreach($item->recepciones as $itemRecepcion){
                $numItems++;
            }
        }
        
        if ($numItems > 0) {
        
            $i = 1;

            $this->SetWidths(array(0.05 * $this->WeightTotal, 0.32 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF', 'DF'));
            $this->SetWidths(array(0.05 * $this->WeightTotal, 0.32 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal));
            $this->SetRounds(array('1', '', '', '', '', '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.6));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', utf8_decode("Descripción"), "Unidad", "Adquirido", "Precio", "Importe", "Entrega", "Recibido", "% Recibido"));

            foreach($this->compra->items as $item){
                foreach($item->recepciones as $itemRecepcion){
                    $this->SetFont('Arial', '', 6);
                    $this->SetWidths(array(0.05 * $this->WeightTotal, 0.32 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal, 0.09 * $this->WeightTotal));
                    $this->encola="items";
                    $this->SetRounds(array('', '', '', '', '', '', '', '', ''));
                    $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0, 0));
                    $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                    $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
                    $this->SetHeights(array(0.35));
                    $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));

                    if ($i == $numItems ) {
                        $this->SetRounds(array('4', '', '', '', '', '', '', '', '3'));
                        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0, 0, 0.2));
                    }

                    $this->SetWidths(array(0.05 * $this->WeightTotal, 0.32 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal,0.09 * $this->WeightTotal));            $this->encola="items";
                    $this->encola = "items";
                    $this->Row(array($i, utf8_decode($item->material->descripcion), utf8_decode($item->unidad), utf8_decode($item->cantidad), utf8_decode($item->precio_unitario), utf8_decode($item->importe), utf8_decode($itemRecepcion->recepcion->fecha_recepcion->format('d-m-Y')), utf8_decode($item->cantidad_recibida), utf8_decode(round(($item->cantidad_recibida / $itemRecepcion->cantidad_recibida) * 100)."%")));

                    $i++;
                }
            }
        } else {
            $this->CellFitScale(19.5, 1, utf8_decode('NO HAY ARTÍCULOS POR MOSTRAR'), 1, 0, 'C');
            $this->Ln(1);
        }
    }
    
    function observaciones(){
        
        if($this->compra->observaciones){
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
            $this->Row(array('  '.$this->compra->observaciones));
        }
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

$pdf = new PDF('p', 'cm', 'Letter', $compra);
$pdf->SetMargins(1, 0.5, 1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->items();
$pdf->observaciones();
$pdf->Output('I', 'CDE - Orden de Compra - # '.$pdf->compra->numero_folio.'.pdf', 1);
exit; 

?>

       