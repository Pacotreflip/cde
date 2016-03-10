<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateCierreRequest extends Request
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
            'id_area' => 'required|array',
        ];
        if(count($this->request->get("id_area"))){
            foreach($this->request->get('id_area') as $key => $val)
            {
                $rules['validacion_completa.'.$key] = 'required|numeric|mayor_a_cero';
            }
        }
        

        return $rules;
    }
    public function messages() {
        $messages = [
            'id_area.array' => 'El cierre de área debe tener al menos una partida.',
            'id_area.required' => 'El cierre de área debe tener al menos una partida.'
        ];
        if(count($this->request->get('id_area'))>0){
            foreach($this->request->get('id_area') as $key => $val)
            {
                $messages['validacion_completa.'.$key.'.mayor_a_cero'] = 'No puede realizar el cierre de área porque hay asignaciones pendientes de validar.';
            }
        }
        
        return $messages;
    }
}
