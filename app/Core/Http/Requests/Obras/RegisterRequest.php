<?php namespace Ghi\Core\Http\Requests\Obras;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'connection' => 'required',
            'nombre' => 'required|max:16',
            'descripcion' => 'required|max:254',
            'estadoObra' => 'required|integer',
            'moneda' => 'required|integer',
            'iva'   => 'required|numeric',
            'fechaInicial' => 'required|date',
            'fechaFinal' => 'required|date',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

}
