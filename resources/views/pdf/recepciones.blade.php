<?php

use Ghidev\Fpdf\Rotation as PDF;
class PDF extends PDF_Rotate {

    var $id_solicitud;
    var $data = array();
    var $encola = "";

    function __construct($p,$cm,$Letter) {
        parent::__construct($p,$cm,$Letter);
        $this->SetAutoPageBreak(true, 3);
    }
}
$pdf = new PDF();
$pdf->SetMargins(1, 0.5, 1);
$pdf->AliasNbPages();
$pdf->AddPage();

//Variables
$txtTitleTam = 18;
$txtSubtitleTam = 13;
$txtSeccionTam = 9;
$txtContenidoTam = 7;
$WeightTotal = $pdf->GetPageWidth() - 2;


//Título
$pdf->SetFont('Arial', 'B', $txtTitleTam);
$pdf->CellFitScale(0.6 * $WeightTotal, 1.5, utf8_decode('Recepción de Artículos - # ' . $recepcion->numero_folio) , 0, 0, 'C', 0);

//Referencias

$pdf->SetWidths(array(0.4 * $WeightTotal));
$pdf->SetRounds(array('0'));
$pdf->SetFills(array('225,225,225'));
$pdf->SetTextColors(array('0,0,0'));
$pdf->SetHeights(array(0.7));
$pdf->SetFont('Arial', 'B', $txtSeccionTam);
$pdf->SetAligns(array('C'));
$pdf->Row(array('Referencias'));


$pdf->SetRounds(array('0'));
$pdf->SetAligns(array('C'));
$pdf->SetFills(array('255,255,255'));
$pdf->SetTextColors(array('0,0,0'));
$pdf->SetHeights(array(0.7));
$pdf->SetFont('Arial', 'B', $txtContenidoTam);
$pdf->SetX(0.6 * $WeightTotal + 1);
$pdf->Cell(0.2 * $WeightTotal, 0.5, utf8_decode('No. de Remisión o Factura:'), 'L', 0, 'L');
$pdf->CellFitScale(0.2 * $WeightTotal, 0.5, utf8_decode($recepcion->numero_remision_factura), 'R', 1, 'C');
$pdf->SetX(0.6 * $WeightTotal + 1);
$pdf->Cell(0.2 * $WeightTotal, 0.5, utf8_decode('Orden de Embarque:'), 'L', 0, 'L');
$pdf->CellFitScale(0.2 * $WeightTotal, 0.5, utf8_decode($recepcion->orden_embarque), 'R', 1, 'C');
$pdf->SetX(0.6 * $WeightTotal + 1);
$pdf->Cell(0.2 * $WeightTotal, 0.5, utf8_decode('Número de Pedimiento:'), 'LB', 0, 'L');
$pdf->CellFitScale(0.2 * $WeightTotal, 0.5, utf8_decode($recepcion->numero_pedimento), 'RB', 1, 'C');
$pdf->Ln(1);

//Subtítulo
$pdf->SetFont('Arial', 'B', $txtSubtitleTam);
$pdf->SetWidths(array($pdf->GetPageWidth() - 2));
$pdf->SetFills(array('0,0,0'));
$pdf->SetTextColors(array('255,255,255'));
$pdf->SetHeights(array(0.7));
$pdf->SetRounds(array('1234'));
$pdf->SetRadius(array(0.2));
$pdf->SetAligns("C");
$pdf->Row(array('Orden de Compra # ' . $recepcion->compra->numero_folio));
$pdf->Ln(1);

//Detalles de la Recepción
$pdf->SetWidths(array(0.75 * $WeightTotal));
$pdf->SetRounds(array('0000'));
$pdf->SetFills(array('225,225,225'));
$pdf->SetTextColors(array('0,0,0'));
$pdf->SetHeights(array(0.7));
$pdf->SetFont('Arial', 'B', $txtSeccionTam);
$pdf->SetAligns(array('C'));
$pdf->SetX(0.125 * $WeightTotal + 1);
$pdf->Row(array(utf8_decode("Detalles de la Recepción")));
$pdf->SetFont('Arial', '', $txtContenidoTam);
$pdf->SetX(0.125 * $WeightTotal + 1);
$pdf->Cell(0.35 * $WeightTotal, 0.5, utf8_decode('Proveedor:'), 'L', 0, 'L');
$pdf->CellFitScale(0.4 * $WeightTotal, 0.5, utf8_decode($recepcion->empresa->razon_social), 'R', 1, 'L');
$pdf->SetX(0.125 * $WeightTotal + 1);
$pdf->Cell(0.35 * $WeightTotal, 0.5, utf8_decode('Fecha Recepción:'), 'L', 0, 'L');
$pdf->CellFitScale(0.4 * $WeightTotal, 0.5, utf8_decode($recepcion->fecha_recepcion->format('Y-m-d h:m') . ' (' . $recepcion->created_at->diffForHumans() . ')'), 'R', 1, 'L');
$pdf->SetX(0.125 * $WeightTotal + 1);
$pdf->Cell(0.35 * $WeightTotal, 0.5, utf8_decode('Persona que Recibió:'), 'L', 0, 'L');
$pdf->CellFitScale(0.4 * $WeightTotal, 0.5, utf8_decode($recepcion->persona_recibio), 'R', 1, 'L');
$pdf->SetX(0.125 * $WeightTotal + 1);
$pdf->Cell(0.35 * $WeightTotal, 0.5, utf8_decode('Persona que Registró:'), 'L', 0, 'L');
$pdf->CellFitScale(0.4 * $WeightTotal, 0.5, utf8_decode($recepcion->usuario_registro->present()->nombreCompleto), 'R', 1, 'L');
$pdf->SetX(0.125 * $WeightTotal + 1);
$pdf->Cell(0.35 * $WeightTotal, 0.5, utf8_decode('Observaciones:'), 'LB', 0, 'L');
$pdf->CellFitScale(0.4 * $WeightTotal, 0.5, utf8_decode($recepcion->observaciones), 'RB', 1, 'L');



$pdf->Output();
exit; 

?>

       