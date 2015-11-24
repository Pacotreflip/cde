<?php

namespace Ghi\Http\Requests;

use Ghi\Http\Requests\Request;

class UpdateAreaTipoRequest extends Request
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
            'nombre' => 'required_without_all:move_up,move_down',
        ];
    }
}
