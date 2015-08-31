<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class UpdateTipoRequest extends Request
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
            'nombre' => 'required|unique:equipamiento.tipos,nombre,'.$this->route('id'),
        ];
    }
}
