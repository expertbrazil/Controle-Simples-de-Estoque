<?php

namespace App\Rules;

class SupplierValidation
{
    public static function rules()
    {
        return [
            'status' => 'required|boolean',
            'tipo_pessoa' => 'required|string|max:1',
            'nome' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'razao_social' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:20',
            'cep' => 'nullable|string|max:10',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'flag' => 'nullable|array',
            'usuario' => 'nullable|string|max:50',
            'senha' => 'nullable|string|max:255',
            'nome_contato' => 'required|string|max:255',
        ];
    }

    public static function messages()
    {
        return [
            'status.required' => 'O status é obrigatório',
            'tipo_pessoa.required' => 'O tipo de pessoa é obrigatório',
            'tipo_pessoa.max' => 'O tipo de pessoa deve ter no máximo 1 caractere',
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres',
            'phone.max' => 'O telefone deve ter no máximo 20 caracteres',
            'razao_social.max' => 'A razão social deve ter no máximo 255 caracteres',
            'documento.max' => 'O documento deve ter no máximo 20 caracteres',
            'cep.max' => 'O CEP deve ter no máximo 10 caracteres',
            'rua.max' => 'A rua deve ter no máximo 255 caracteres',
            'numero.max' => 'O número deve ter no máximo 10 caracteres',
            'complemento.max' => 'O complemento deve ter no máximo 255 caracteres',
            'bairro.max' => 'O bairro deve ter no máximo 255 caracteres',
            'cidade.max' => 'A cidade deve ter no máximo 100 caracteres',
            'uf.max' => 'O UF deve ter no máximo 2 caracteres',
            'whatsapp.max' => 'O WhatsApp deve ter no máximo 20 caracteres',
            'email.email' => 'O e-mail informado não é válido',
            'email.max' => 'O e-mail deve ter no máximo 255 caracteres',
            'usuario.max' => 'O usuário deve ter no máximo 50 caracteres',
            'senha.max' => 'A senha deve ter no máximo 255 caracteres',
            'nome_contato.required' => 'O nome do contato é obrigatório',
            'nome_contato.max' => 'O nome do contato deve ter no máximo 255 caracteres',
        ];
    }
}
