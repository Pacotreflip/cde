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
            'descripcion' => 'required',
            'id_propiedad' => 'integer',
            'id_categoria' => 'integer',
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
