<?php

use Ghidev\Fpdf\Rotation;

class PDF extends Rotation {
    
    var $compra;
    var $WidthTotal;
    var $txtTitleTam, $txtSubtitleTam, $txtSeccionTam, $txtContenidoTam, $txtFooterTam;
    var $encola = "";
    
    function __construct($p,$cm,$Letter, $compra) {
        
        parent::__construct($p,$cm,$Letter);
        $this->SetAutoPageBreak(true,4.5);
        $this->compra = $compra;
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
        
        //Tabla Detalles de la Compra
        $this->detallesCompra();

        //Posiciones despues de la tabla detalles
        $y_final = $this->getY();
        $this->setY($y_inicial);
         
        $alto = abs($y_final - $y_inicial);
        
        //Redondear Bordes Detalles Compra
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
        
        //Tabla Detalles de la Compra
        $this->setY($y_inicial);
        $this->setX($x_inicial);      
        $this->detallesCompra();
        
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
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ADQUIRIDOS'), 0, 1, 'L');
            
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.42 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'FD', 'DF', 'DF', 'DF', 'DF'));
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.42 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '',  '', '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0,  0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.6));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', utf8_decode("Descripción"), "Unidad", "Adquirido", "Precio", "Importe", "Recibido", "% Recibido"));

            $this->SetRounds(array('', '', '', '', '', '', '', ''));
            $this->SetRadius(array(0, 0, 0, 0, 0, 0, 0, 0));
            $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.35));
            $this->SetAligns(array('C', 'L', 'L', 'R', 'R', 'R', 'R', 'C'));
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
        $this->SetFont('Arial', 'B', $this->txtTitleTam);
        $this->CellFitScale(0.6 * $this->WidthTotal, 1.5, utf8_decode('Compra de Artículos - #' . $this->compra->numero_folio), 0, 1, 'L', 0);
        $this->Line(1, $this->GetY() + 0.2, $this->WidthTotal + 1, $this->GetY() + 0.2);
        $this->Ln(0.5);
        
        //Detalles de la Compra (Titulo)
        $this->SetFont('Arial', 'B', $this->txtSeccionTam);
        $this->Cell(0.55 * $this->WidthTotal,.7,utf8_decode('Detalle de la Compra'),0,1,'L');
    }
    
    function detallesCompra(){
        
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('No. Folio:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode('# ' . $this->compra->numero_folio), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Fecha:'), '', 0, 'L');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->compra->fecha->format('d-M-Y h:m A')), '', 1, 'L');
        $this->SetFont('Arial', 'B', $this->txtContenidoTam);
        $this->Cell(0.15 * $this->WidthTotal, 0.5, utf8_decode('Proveedor:'), '', 0, 'LB');
        $this->SetFont('Arial', '', $this->txtContenidoTam);
        $this->CellFitScale(0.4 * $this->WidthTotal, 0.5, utf8_decode($this->compra->empresa->razon_social), '', 1, 'L');
    }
    
    function items(){
                
        $numItems = 0;
        
        foreach($this->compra->items as $item){
            
                $numItems++;
            
        }
        
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
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ADQUIRIDOS'), 0, 1, 'L');

            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.42 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal));
            $this->SetFont('Arial', '', 6);
            $this->SetStyles(array('DF', 'DF', 'DF', 'DF', 'FD', 'DF',  'DF', 'DF'));
            $this->SetWidths(array(0.04 * $this->WidthTotal, 0.42 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal,  0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal));
            $this->SetRounds(array('1', '', '', '', '',  '', '', '2'));
            $this->SetRadius(array(0.2, 0, 0, 0, 0, 0,  0, 0.2));
            $this->SetFills(array('180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180', '180,180,180'));
            $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0'));
            $this->SetHeights(array(0.6));
            $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $this->Row(array('#', utf8_decode("Descripción"), "Unidad", "Adquirido", "Precio", "Importe",  "Recibido", "% Recibido"));

            foreach($this->compra->items as $item){
                
                    $this->SetFont('Arial', '', 6);
                    $this->SetWidths(array(0.04 * $this->WidthTotal, 0.42 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal,  0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal));
                    $this->encola="items";
                    $this->SetRounds(array('', '', '', '', '', '',  '', ''));
                    $this->SetRadius(array(0, 0, 0, 0, 0, 0,  0, 0));
                    $this->SetFills(array('255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255', '255,255,255'));
                    $this->SetTextColors(array('0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0', '0,0,0',  '0,0,0'));
                    $this->SetHeights(array(0.35));
                    $this->SetAligns(array('C', 'L', 'L', 'R', 'R', 'R',  'R', 'C'));

                    if ($i == $numItems ) {
                        $this->SetRounds(array('4', '', '', '', '',  '', '', '3'));
                        $this->SetRadius(array(0.2, 0, 0, 0, 0, 0, 0,  0.2));
                    }

                    $this->SetWidths(array(0.04 * $this->WidthTotal, 0.42 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal, 0.09 * $this->WidthTotal));
                    $this->encola = "items";
//                    for($cont = 0; $cont < 50; $cont++){
                    $this->Row(array($i, utf8_decode($item->material->descripcion), utf8_decode($item->unidad), number_format(utf8_decode($item->cantidad),0,'.',','), number_format(utf8_decode($item->precio_unitario),2,'.',','), number_format(utf8_decode($item->importe),2,'.',','),  utf8_decode($item->cantidad_recibida), utf8_decode(round(($item->cantidad_recibida / $item->cantidad) * 100)."%")));
//                    }
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
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('ARTÍCULOS ADQUIRIDOS'), 0, 1, 'C');
            
            $this->SetFont('Arial', 'B', $this->txtContenidoTam);
            $this->CellFitScale($this->WidthTotal, 1, utf8_decode('NO HAY ARTÍCULOS POR MOSTRAR'), 1, 0, 'C');
            $this->Ln(1);
        }
    }
    
    function observaciones(){
        
        $this->encola = "";
        
        if($this->compra->observaciones){
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
            $this->Row(array(utf8_decode($this->compra->observaciones)));
        }
    }
    
    function logo(){
        $this->image(public_path('img/logo_hc.png'), $this->WidthTotal - 1.3, 0.5, 2.33, 1.5);       
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
$pdf->Ln(0.5);
$pdf->observaciones();
$pdf->Output('I', 'Orden_de_compra_'.$pdf->compra->numero_folio.'.pdf', 1);

exit; 

?>   