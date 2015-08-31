<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateSubtipoRequest extends Request
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
        $tipo_id = $this->route('tipo_id');
        return [
            'nombre' => 'required|unique:equipamiento.subtipos,nombre,null,id,tipo_id,'.$tipo_id,
        ];
    }
}
