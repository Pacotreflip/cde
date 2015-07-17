<?php

namespace Ghi\Http\Requests\Almacenes;

use Ghi\Http\Requests\Request;

class ActualizaAlmacenMaquinariaRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'numero_economico' => 'required',
            'descripcion'   => 'required',
            'propiedad'     => 'required|string',
            'clasificacion' => 'required|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
