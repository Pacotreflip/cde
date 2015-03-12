<?php namespace Ghi\Operacion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InicioActividadesRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'fecha' => 'required|date',
            'horometro_inicial' => 'numeric',
            'kilometraje_inicial' => 'integer',
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
