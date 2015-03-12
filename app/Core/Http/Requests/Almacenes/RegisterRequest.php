<?php namespace Ghi\Core\Http\Requests\Almacenes;

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
            'descripcion' => 'required',
            'material' => 'required|integer',
            'economico' => 'required',
            'propiedad' => 'required|integer',
            'categoria' => 'required|integer'
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
