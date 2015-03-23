<?php namespace Ghi\Operacion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportarActividadRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'tipo_hora' => 'required|integer',
			'cantidad' => 'required|numeric|max:24|min:1',
			'id_concepto' => 'required_if:tipo_hora,1',
            'hora_inicial' => ''
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
