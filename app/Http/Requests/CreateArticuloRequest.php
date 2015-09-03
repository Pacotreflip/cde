<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateArticuloRequest extends Request
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
            'nombre'             => 'required|unique:equipamiento.articulos,nombre',
            'unidad'             => 'required_if:nueva_unidad,null',
            'nueva_unidad'       => 'required_without:unidad',
            'clasificador'       => 'required_if:clasificador,null',
            'nuevo_clasificador' => 'required_without:clasificador',
            'ficha_tecnica'      => 'mimes:pdf',
        ];
    }
}
