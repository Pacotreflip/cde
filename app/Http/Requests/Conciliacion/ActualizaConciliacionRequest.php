<?php

namespace Ghi\Http\Requests\Conciliacion;

use Ghi\Http\Requests\Request;

class ActualizaConciliacionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'horas_efectivas_conciliadas'  => 'required|integer|min:0',
            'horas_ocio_conciliadas'       => 'required|integer|min:0',
            'horas_reparacion_conciliadas' => 'required|integer|min:0',
        ];
    }
}
