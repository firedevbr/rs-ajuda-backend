<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltrarDesaparecimentoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pessoa' => 'sometimes|string|max:255|nullable',
            'status' => 'sometimes|string|max:255|nullable',
            'cidade' => 'sometimes|string|max:255|nullable',
            'bairro' => 'sometimes|string|max:255|nullable',
            'responsavel_id' => 'sometimes|exists:usuarios,id|nullable',
            'ultimo_visto_em' => 'sometimes|date|nullable',
            'meus' => 'sometimes|string|nullable',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100'
        ];
    }
}
