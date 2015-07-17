<?php

namespace Ghi\Http\Requests\Almacenes;

use Ghi\Http\Requests\Request;

class RegistraAlmacenMaquinariaRequest extends Request
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
            'descripcion' => 'required',
            'id_material' => 'required|integer',
            'propiedad' => 'required',
            'clasificacion' => 'required'
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
