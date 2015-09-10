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
            'razon_social' => 'required|unique:cadeco.empresas,razon_social',
            'tipo_empresa' => 'required',
        ];
    }
}
