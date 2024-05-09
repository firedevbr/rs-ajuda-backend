<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnderecoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'logradouro' => 'string|max:255|nullable',
            'bairro' => 'string|max:255|required',
            'regiao' => 'string|max:255|nullable',
            'numero' => 'string|max:255|nullable',
            'cep' => 'string|max:255|nullable',
            'cidade' => 'string|max:255|required',
            'estado' => 'string|max:255|required',
            'ponto_de_referencia' => 'string|max:255|nullable',
        ];
    }
}
