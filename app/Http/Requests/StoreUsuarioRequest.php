<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'string|max:255|required',
            'email' => 'string|max:255|required',
            'password' => 'string|max:255|required',
            'cpf' => 'string|max:255|required'
        ];
    }
}
