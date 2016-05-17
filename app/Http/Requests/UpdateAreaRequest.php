<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class UpdateAreaRequest extends Request
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
            'nombre' => 'required',
            'tipo_id' => 'integer',
            'almacen_id'=>'unique:cadeco.Equipamiento.areas,id_almacen,'. $this->area_id
        ];
    }
    
    public function messages()
    {
        $messages = [
            'almacen_id.unique' => 'Un almacén del módulo de ordebes de compra del SAO sólo puede estar relacionado a una ubicación del módulo de equipamiento.'
        ];

        return $messages;
    }
}
