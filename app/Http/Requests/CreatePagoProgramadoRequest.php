<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreatePagoProgramadoRequest extends Request
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
            'monto' => 'required|numeric|max:'.$this->faltante,
            'fecha' => 'required',
            'observaciones' => 'required'
        ];
    }
    
     public function messages() {
        $messages = [
            'monto.required' => 'Favor de indicar el monto.',
            'monto.max' => 'El monto a programar debe ser menor o igual al monto faltante ('.$this->faltante.')',
            'fecha.required' => 'Por favor indicar la fecha de pago programado.',
            'observaciones.required' => 'Favor de indicar las observaciones.'
        ];
        
        return $messages;
    }
}
