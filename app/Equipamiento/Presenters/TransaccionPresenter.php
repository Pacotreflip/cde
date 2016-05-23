<?php
namespace Ghi\Equipamiento\Presenters;
use Laracasts\Presenter\Presenter;
use Ghi\Equipamiento\Transacciones\Transaccion;
class TransaccionPresenter extends Presenter{
    
    public function numero_folio(){
        return "# " . $this->zerofill(4, $this->entity->numero_folio);
    }
    private function zerofill( $longitud, $valor){
        $cad_ceros = "";
        $cantidad = $longitud - strlen($valor);
        for($i=0; $i<$cantidad; $i++){
            $cad_ceros.='0';
        }
        return $cad_ceros . $valor;
    }
}
