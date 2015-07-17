<?php

namespace Ghi\Http\Requests\ReportesActividad;

use Ghi\Http\Requests\Request;

class ReportarActividadRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo_hora' => 'required',
            'cantidad' => 'required|numeric|max:24|min:0.1',
            'id_concepto' => 'required_if:tipo_hora,EF',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
