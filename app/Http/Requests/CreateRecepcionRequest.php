<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateRecepcionRequest extends Request
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
            'proveedor'       => 'required',
            'fecha_recepcion' => 'required|date',
            'orden_compra'    => 'required',
            'persona_recibe'  => 'required',
        ];
    }
}
