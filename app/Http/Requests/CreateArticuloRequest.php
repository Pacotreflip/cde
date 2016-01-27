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
            'descripcion'                   => 'required|unique:cadeco.materiales,descripcion',
            'unidad'                        => 'required_if:nueva_unidad,null',
            'nueva_unidad'                  => 'required_without:unidad|unique:cadeco.unidades,unidad',
            'ficha_tecnica'                 => 'mimes:pdf',
            'familia'                       => 'required',
            'precio_estimado'               => 'numeric',
            'precio_proyecto_comparativo'   => 'numeric',
            'moneda'                        => 'required_with:precio_estimado',
            'moneda_proyecto_comparativo'   => 'required_with:precio_proyecto_comparativo',
        ];
    }
}
