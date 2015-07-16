<?php

namespace Ghi\Http\Requests\Conciliacion;

use Ghi\Http\Requests\Request;

class RegistraConciliacionRequest extends Request
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
            'fecha_inicial' => 'required|date',
            'fecha_final'   => 'required|date',
        ];
    }
}
