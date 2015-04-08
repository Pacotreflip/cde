<?php namespace Ghi\Almacenes\Http\Requests;

use Ghi\Core\Http\Requests\Request;

class RegistrarHorasMensualesRequest extends Request
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
			'inicio_vigencia' => 'date|required',
            'horas_contrato' => 'integer|required',
            'horas_operacion' => 'integer',
            'horas_programa' => 'integer'
		];
	}

}
