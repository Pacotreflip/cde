<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateAreaRequest extends Request
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
            'nombre' => 'required',
            'cantidad' => 'integer|min:1',
            'rango_inicial' => 'integer|min:1',
            'subtipo_id' => 'integer',
        ];
    }
}
