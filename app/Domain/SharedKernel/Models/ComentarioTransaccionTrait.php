<?php namespace Ghi\SharedKernel\Models;

use Carbon\Carbon;

trait ComentarioTransaccionTrait {

    /**
     * @param $usuario
     * @return string
     */
    protected function getComentarioRegistro($usuario)
    {
        return 'I;' . Carbon::now()->format('d-m-Y h:m') . ';' . $usuario . '|';
    }

}
