<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class MuestraArticulosAreaSeleccionadaRequest extends Request
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
            'id_tipo_area'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id_tipo_area.required' => 'Seleccione una área tipo para copiar',
        ];
    }
}
