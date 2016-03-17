<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateEntregaRequest extends Request
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
        $rules = [
            
            'fecha_entrega' => 'required',
            'concepto' => 'required',
            'entrega' => 'required',
            'recibe' => 'required',
            'id_articulo' => 'required|array',
            
        ];
       
        

        return $rules;
    }
    public function messages() {
        $messages = [
            'fecha_entrega.required' => 'Favor de indicar la fecha de entrega.',
            'id_articulo.array' => 'La entrega de área debe tener al menos una partida.',
            'id_articulo.required' => 'La entrega de área debe tener al menos una partida.',
            'concepto.required' => 'Favor de indicar el concepto de la entrega.',
            'entrega.required' => 'Favor de indicar que persona esta haciendo la entrega de artículos.',
            'recibe.required' => 'Favor de indicar que persona esta recibiendo los artículos.'
        ];
        
        
        return $messages;
    }
}
