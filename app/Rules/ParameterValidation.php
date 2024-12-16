<?php

namespace App\Rules;

class ParameterValidation
{
    public static function rules()
    {
        return [
            'company_name' => 'required|string|max:255',
            'company_document' => 'nullable|string|max:20',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string|max:255',
            'system_logo' => 'nullable|image|max:2048'
        ];
    }

    public static function messages()
    {
        return [
            'company_name.required' => 'O nome da empresa é obrigatório',
            'company_name.max' => 'O nome da empresa não pode ter mais que 255 caracteres',
            'company_document.max' => 'O documento não pode ter mais que 20 caracteres',
            'company_phone.max' => 'O telefone não pode ter mais que 20 caracteres',
            'company_email.email' => 'O e-mail informado não é válido',
            'company_email.max' => 'O e-mail não pode ter mais que 255 caracteres',
            'company_address.max' => 'O endereço não pode ter mais que 255 caracteres',
            'system_logo.image' => 'O arquivo deve ser uma imagem',
            'system_logo.max' => 'A imagem não pode ter mais que 2MB'
        ];
    }
}
