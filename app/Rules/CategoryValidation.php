<?php

namespace App\Rules;

class CategoryValidation
{
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'active' => 'boolean'
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'O nome da categoria é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'parent_id.exists' => 'A categoria pai selecionada não existe'
        ];
    }
}
