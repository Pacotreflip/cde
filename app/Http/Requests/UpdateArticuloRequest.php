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
            'descripcion'                   => 'required|max:255|unique:cadeco.materiales,descripcion,'.$this->route('id').',id_material',
            'numero_parte'                  => 'max:16|unique:cadeco.materiales,numero_parte,'.$this->route('id').',id_material',
            'unidad'                        => 'required',
            'familia'                       => 'required',
            'precio_estimado'               => 'numeric',
            'precio_proyecto_comparativo'   => 'numeric',
            'moneda'                        => 'required_with:precio_estimado',
            'moneda_proyecto_comparativo'   => 'required_with:precio_proyecto_comparativo',
            'ficha_tecnica'                 => 'mimes:pdf,png,jpg',
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
