<?php namespace Ghi\Almacenes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistraAlmacenMaquinariaRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'numero_economico' => 'required',
            'descripcion' => 'required',
            'id_material' => 'required|integer',
            'id_propiedad' => 'required|integer',
            'id_categoria' => 'required|integer'
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
