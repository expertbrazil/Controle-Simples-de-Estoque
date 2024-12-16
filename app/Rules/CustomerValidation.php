<?php

namespace App\Rules;

class CustomerValidation
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'document' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'active' => 'boolean'
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'O nome do cliente é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'email.email' => 'O e-mail informado não é válido',
            'phone.required' => 'O telefone é obrigatório',
            'phone.max' => 'O telefone não pode ter mais que 20 caracteres',
            'document.max' => 'O documento não pode ter mais que 20 caracteres',
            'address.max' => 'O endereço não pode ter mais que 255 caracteres'
        ];
    }
}
