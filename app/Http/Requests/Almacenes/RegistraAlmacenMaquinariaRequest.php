<?php

namespace Ghi\Http\Requests\Almacenes;

use Ghi\Domain\Core\Facades\Context;
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
        $id_obra = Context::getId();

        return [
            'numero_economico' => 'required|unique:cadeco.almacenes,numero_economico,null,id_almacen,id_obra,'.$id_obra,
            'descripcion'      => 'required',
            'id_material'      => 'required|integer',
            'propiedad'        => 'required',
            'clasificacion'    => 'required'
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
