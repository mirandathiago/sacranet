<?php

namespace Sacranet\Http\Requests;

use Sacranet\Http\Requests\Request;

class TipoOcorrenciaRequest extends Request
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
           'descricao' => 'required',
            'tipo'     => 'required'
        ];
    }
}
