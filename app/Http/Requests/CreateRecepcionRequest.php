<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class CreateRecepcionRequest extends Request
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
            'fecha_recepcion'     => 'required|date',
            'orden_compra'        => 'required',
            'persona_recibio'     => 'required',
            'area_almacenamiento' => 'required',
            'materiales'          => 'required|array',
        ];

        foreach ($this->get('materiales') as $key => $value) {
            $rules['materiales.'.$key] = 'array';
            $rules['materiales.'.$key.'.id'] = 'required|integer';
            $rules['materiales.'.$key.'.cantidad_recibir'] = 'numeric';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'orden_compra.required'        => 'El campo folio orden de compra es obligatorio.',
            'persona_recibio.required'     => 'El campo persona que recibe es obligatorio.',
            'area_almacenamiento.required' => 'El campo area de almacenamiento es obligatorio.',
            'materiales.required'          => 'Debe agregar por lo menos un articulo a recibir.',
        ];

        foreach ($this->get('materiales') as $key => $value) {
            $messages['materiales.'.$key.'.id.required'] = "El identificador del artículo [{$value['numero_parte']}]-{$value['descripcion']} no es válido.";
            $messages['materiales.'.$key.'.id.required'] = "El identificador del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser un numero entero.";
            $messages['materiales.'.$key.'.cantidad_recibir.numeric'] = "El campo cantidad del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser numérico.";
            $messages['materiales.'.$key.'.cantidad_recibir.min'] = "El campo cantidad del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser mínimo :min.";
        }

        return $messages;
    }
}
