<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class AgregaComprobanteRequest extends Request
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
            'comprobante' => 'required|mimes:jpeg,bmp,png,jpg,pdf',
        ];
    }
}
