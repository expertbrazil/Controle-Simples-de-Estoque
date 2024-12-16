<?php

namespace App\Rules;

class SupplierValidation
{
    public static function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'tipo_pessoa' => 'required|in:F,J',
            'documento' => 'required|string|max:18|unique:suppliers,documento',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'cep' => 'nullable|string|max:9',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'uf' => 'nullable|string|size:2',
            'status' => 'boolean'
        ];
    }

    public static function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres',
            'tipo_pessoa.required' => 'O tipo de pessoa é obrigatório',
            'tipo_pessoa.in' => 'O tipo de pessoa deve ser Física (F) ou Jurídica (J)',
            'documento.required' => 'O CPF/CNPJ é obrigatório',
            'documento.max' => 'O CPF/CNPJ não pode ter mais de 18 caracteres',
            'documento.unique' => 'Este CPF/CNPJ já está cadastrado',
            'email.email' => 'O e-mail deve ser um endereço válido',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres',
            'phone.max' => 'O telefone não pode ter mais de 15 caracteres',
            'whatsapp.max' => 'O WhatsApp não pode ter mais de 15 caracteres',
            'cep.max' => 'O CEP não pode ter mais de 9 caracteres',
            'rua.max' => 'O endereço não pode ter mais de 255 caracteres',
            'numero.max' => 'O número não pode ter mais de 10 caracteres',
            'complemento.max' => 'O complemento não pode ter mais de 255 caracteres',
            'bairro.max' => 'O bairro não pode ter mais de 255 caracteres',
            'cidade.max' => 'A cidade não pode ter mais de 255 caracteres',
            'uf.size' => 'O estado deve ter 2 caracteres'
        ];
    }
}
