<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltrarEnderecoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'logradouro' => 'sometimes|string|max:255|nullable',
            'bairro' => 'sometimes|string|max:255|nullable',
            'regiao' => 'sometimes|string|max:255|nullable',
            'numero' => 'sometimes|string|max:255|nullable',
            'cep' => 'sometimes|string|max:255|nullable',
            'cidade_id' => 'sometimes|exists:cidades,id|nullable',
            'ponto_de_referencia' => 'sometimes|string|max:255|nullable',
            'latitude' => 'sometimes|string|max:255|nullable',
            'longitude' => 'sometimes|integer|nullable',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100'
        ];
    }
}