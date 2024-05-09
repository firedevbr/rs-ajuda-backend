<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesaparecimentoRequest extends FormRequest
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
            'status' => 'string|max:255|required',
            'responsavel_id' => 'exists:usuarios,id|nullable',
            'ultimo_visto_em' => 'date|required'
        ];
    }
}