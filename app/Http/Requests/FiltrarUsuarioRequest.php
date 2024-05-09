<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiltrarUsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'sometimes|string|max:255|nullable',
            'email' => 'sometimes|string|max:255|nullable',
            'password' => 'sometimes|string|max:255|nullable',
            'cpf' => 'sometimes|string|max:255|nullable',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100'
        ];
    }
}