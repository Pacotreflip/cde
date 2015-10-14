<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateTransferenciaRequest extends Request
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
            'fecha'       => 'required|date',
            'area_origen' => 'required|integer',
            'materiales' => 'required|array',
        ];

        foreach ($this->get('materiales') as $key => $value) {
            $rules['materiales.'.$key.'.cantidad'] = 'numeric';
            $rules['materiales.'.$key.'.area_destino'] = 'required_with:materiales.'.$key.'.cantidad|integer';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'materiales.required' => 'Debe especificar por lo menos un articulo a transferir.'
        ];

        foreach ($this->get('materiales') as $key => $value) {
            $messages['materiales.'.$key.'.cantidad.numeric'] = "El campo cantidad del articulo {$value['descripcion']} debe ser numerico.";
            $messages['materiales.'.$key.'.area_destino.required_with'] = "El campo destino del articulo {$value['descripcion']} es obligatorio.";;
        }

        return $messages;
    }
}
