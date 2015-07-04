<?php

namespace Ghi\Http\Requests\Almacenes;

use Ghi\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class RegistrarHorasMensualesRequest extends Request
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
            'inicio_vigencia' => 'date|required|unique:cadeco.Maquinaria.horas_mensuales,inicio_vigencia,'.$this->route('id').',id,id_almacen,'. $this->route('idAlmacen'),
            'horas_contrato' => 'integer|required',
            'horas_operacion' => 'integer',
            'horas_programa' => 'integer'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function messages()
    {
        return ['inicio_vigencia.unique' => 'Ya existe un registro con la fecha indicada, seleccione una diferente.'];
    }
}
