<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResgateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pessoa_id' => 'exists:pessoas,id|required',
            'endereco_id' => 'exists:enderecos,id|required',
            'tipo' => 'string|max:255|required',
            'observacoes' => 'string|nullable',
            'ultimo_visto_em' => 'date|required'
        ];
    }
}
