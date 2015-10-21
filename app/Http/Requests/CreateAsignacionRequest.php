<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateAsignacionRequest extends Request
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
            'origen' => 'required',
            'materiales' => 'required|array',
        ];

        foreach ($this->get('materiales', []) as $key => $material) {
            foreach ($material['destinos'] as $destinoKey => $destino) {
                $rules['materiales.'.$key.'.destinos.'.$destinoKey.'.cantidad'] = 'required|numeric';
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'origen.required' => 'Debe especificar el area origen.',
            'materiales.required' => 'Debe especificar por lo menos un material para asignar.',
        ];

        foreach ($this->get('materiales', []) as $key => $material) {
            foreach ($material['destinos'] as $destinoKey => $destino) {
                $messages['materiales.'.$key.'.destinos.'.$destinoKey.'.cantidad.required'] = 
                    sprintf('La cantidad del destino "%s" en el articulo "%s" es obligatoria.', $destino['path'], $material['descripcion']);
                $messages['materiales.'.$key.'.destinos.'.$destinoKey.'.cantidad.numeric'] = 
                    sprintf('La cantidad del destino "%s" en el articulo "%s" es obligatoria.', $destino['path'], $material['descripcion']);
            }
        }

        return $messages;
    }
}
