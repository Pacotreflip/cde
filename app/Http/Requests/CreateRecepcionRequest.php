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
            'fecha_recepcion' => 'required|date',
            'orden_compra'    => 'required',
            'numero_remision_factura'    => 'required',
            'persona_recibio' => 'required',
            'materiales'      => 'required|array',
        ];

        foreach ($this->get('materiales') as $key => $material) {
            $rules['materiales.'.$key] = 'array';
            $rules['materiales.'.$key.'.id'] = 'required|integer';
            $rules['materiales.'.$key.'.destinos'] = 'array';
            
            foreach ($material['destinos'] as $keyDestino => $destino) {
                $rules['materiales.'.$key.'.destinos.'.$keyDestino.'.cantidad'] = 'required|numeric|min:1';
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'orden_compra.required'    => 'El campo folio orden de compra es obligatorio.',
            'persona_recibio.required' => 'El campo persona que recibe es obligatorio.',
            'materiales.required'      => 'Debe recibir por lo menos un articulo.',
        ];

        foreach ($this->get('materiales') as $key => $material) {
            $messages['materiales.'.$key.'.id.required'] = 
                "El identificador del artículo [{$material['numero_parte']}]-{$material['descripcion']} no es válido.";

            $messages['materiales.'.$key.'.id.required'] = 
                "El identificador del artículo [{$material['numero_parte']}]-{$material['descripcion']} debe ser un numero entero.";

            $messages['materiales.'.$key.'.cantidad_recibir.min'] = 
                "El campo cantidad del artículo [{$material['numero_parte']}]-{$material['descripcion']} debe ser mínimo :min.";

            foreach ($material['destinos'] as $keyDestino => $destino) {
                $messages['materiales.'.$key.'.destinos.'.$keyDestino.'.cantidad.min'] = 
                    "La cantidad del destino {$destino['ruta']} del articulo [{$material['numero_parte']}]-{$material['descripcion']} debe ser de al menos 1";
            }
        }

        return $messages;
    }
}
