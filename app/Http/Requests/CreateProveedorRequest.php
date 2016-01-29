<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateProveedorRequest extends Request
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
            'razon_social' => 'required|unique:cadeco.empresas,razon_social|max:255',
            'nombre_corto' => 'required|unique:cadeco.empresas,nombre_corto|max:60',
            'tipo_empresa' => 'required',
            'nombre_contacto' => 'max:60|min:10',
            'correo' => 'email',
        ];
    }
}
