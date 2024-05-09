<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVaquinhaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pessoa_id' => 'exists:pessoas,id|required',
            'descricao_objetivo' => 'string|required',
            'pix' => 'string|max:255|nullable',
            'dados_bancarios' => 'string|max:255|nullable',
            'endereco_itens_ajuda_id' => 'exists:enderecos,id|required',
            'status' => 'string|max:255|nullable',
            'responsavel_id' => 'exists:usuarios,id|nullable',
            'aberto_desde' => 'string|nullable'
        ];
    }
}