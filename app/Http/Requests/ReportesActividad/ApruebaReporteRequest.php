<?php

namespace Ghi\Http\Requests\ReportesActividad;

use Ghi\Http\Requests\Request;

class ApruebaReporteRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'horometro_final' => 'required_with:horometro_inicial',
            'kilometraje_final' => 'required_with:kilometraje_inicial',
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
