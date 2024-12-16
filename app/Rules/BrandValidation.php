<?php

namespace App\Rules;

class BrandValidation
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'active' => 'boolean'
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'O nome da marca é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres'
        ];
    }
}
