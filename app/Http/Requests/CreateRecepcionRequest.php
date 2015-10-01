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
            'persona_recibe'      => 'required',
            'area_almacenamiento' => 'required',
            'materiales'          => 'required|array',
        ];

        foreach ($this->get('materiales') as $key => $value) {
            $rules['materiales.'.$key] = 'array';
            $rules['materiales.'.$key.'.id'] = 'required|integer';
            $rules['materiales.'.$key.'.cantidad_recibir'] = 'numeric|min:1';
            // $rules['materiales.'.$key.'.precio'] = 'required|numeric|min:1';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'orden_compra.required'        => 'El campo folio orden de compra es obligatorio.',
            'persona_recibe.required'      => 'El campo persona que recibe es obligatorio.',
            'area_almacenamiento.required' => 'El campo area de almacenamiento es obligatorio.',
            'materiales.required'          => 'Debe agregar por lo menos un articulo a recibir.',
        ];

        foreach ($this->get('materiales') as $key => $value) {
            $messages['materiales.'.$key.'.id.required'] = "El identificador del artículo [{$value['numero_parte']}]-{$value['descripcion']} no es válido.";
            $messages['materiales.'.$key.'.id.required'] = "El identificador del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser un numero entero.";
            $messages['materiales.'.$key.'.cantidad_recibir.numeric'] = "El campo cantidad del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser numerico.";
            $messages['materiales.'.$key.'.cantidad_recibir.min'] = "El campo cantidad del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser mínimo :min.";
            // $messages['materiales.'.$key.'.precio.required'] = "El campo precio del artículo [{$value['numero_parte']}]-{$value['descripcion']} es obligatorio.";
            // $messages['materiales.'.$key.'.precio.numeric'] = "El campo precio del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser numerico.";
            // $messages['materiales.'.$key.'.precio.min'] = "El campo precio del artículo [{$value['numero_parte']}]-{$value['descripcion']} debe ser mínimo :min.";
        }

        return $messages;
    }
}
