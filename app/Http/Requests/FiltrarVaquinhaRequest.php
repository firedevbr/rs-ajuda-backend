<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltrarVaquinhaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pessoa' => 'sometimes|string|max:255|nullable',
            'descricao_objetivo' => 'sometimes|string|nullable',
            'pix' => 'sometimes|string|max:255|nullable',
            'dados_bancarios' => 'sometimes|string|max:255|nullable',
            'endereco_itens_ajuda_id' => 'sometimes|exists:enderecos,id|nullable',
            'status' => 'sometimes|string|max:255|nullable',
            'cidade' => 'sometimes|string|max:255|nullable',
            'bairro' => 'sometimes|string|max:255|nullable',
            'responsavel_id' => 'sometimes|exists:usuarios,id|nullable',
            'aberto_desde' => 'sometimes|string|nullable',
            'meus' => 'sometimes|string|nullable',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100'
        ];
    }
}
