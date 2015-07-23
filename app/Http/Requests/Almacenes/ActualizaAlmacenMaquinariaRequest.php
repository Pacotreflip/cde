<?php

namespace Ghi\Http\Requests\Almacenes;

use Ghi\Domain\Core\Facades\Context;
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
        $id_obra = Context::getId();

        return [
            'numero_economico' => 'required|unique:cadeco.almacenes,numero_economico,'.$this->route('id').',id_almacen,id_obra,'.$id_obra,
            'descripcion'      => 'required',
            'propiedad'        => 'required|string',
            'clasificacion'    => 'required|string',
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
