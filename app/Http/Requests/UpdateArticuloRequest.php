<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class UpdateArticuloRequest extends Request
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
            'descripcion'     => 'required|max:255|unique:cadeco.materiales,descripcion,'.$this->route('id').',id_material',
            'numero_parte'    => 'required|max:255|unique:cadeco.materiales,numero_parte,'.$this->route('id').',id_material',
            'unidad'          => 'required',
            'familia'         => 'required',
            'ficha_tecnica'   => 'mimes:pdf',
        ];
    }

    public function messages()
    {
        return [
            'descripcion.unique' => 'La descripción de este artículo ya ha sido registrada.',
            'numero_parte.unique' => 'El numero de parte de este artículo ya ha sido registrado.'
        ];
    }
}
