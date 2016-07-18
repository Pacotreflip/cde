<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class UpdateEntregaProgramadaRequest extends Request
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
            'cantidad' => 'required|integer|numeric|min:1|max:'.($this->actual + $this->faltante),
            'fecha_entrega' => 'required',
            'observaciones' => 'required'
        ];
    }
    
     public function messages() {
        $messages = [
            'cantidad.required' => 'Favor de indicar la cantidad.',
            'cantidad.max' => 'La cantidad a programar debe ser menor o igual a la cantidad programada actual más la cantidad faltante ('.($this->actual + $this->faltante).')',
            'fecha_entrega.required' => 'Por favor indicar la fecha de entrega programada.',
            'observaciones.required' => 'Favor de indicar las observaciones.'
        ];
        
        return $messages;
    }
}
