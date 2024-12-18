<?php

namespace App\Rules;

use App\Models\Supplier;
use Illuminate\Validation\Rule;

class SupplierValidation
{
    public static function rules($supplierId = null)
    {
        $rules = [
            'status' => 'required|boolean',
            'tipo_pessoa' => 'required|string|max:1',
            'nome' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'razao_social' => 'nullable|string|max:255',
            'documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('suppliers', 'documento')->ignore($supplierId)
            ],
            'cep' => 'nullable|string|max:10',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|max:2',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'nome_contato' => 'required|string|max:255',
            'inscricao_estadual' => 'nullable|string|max:20',
            'inscricao_municipal' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string|max:1000'
        ];

        return $rules;
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
            'documento.required' => 'O CPF/CNPJ é obrigatório',
            'documento.max' => 'O documento deve ter no máximo 20 caracteres',
            'documento.unique' => 'Este CPF/CNPJ já está cadastrado',
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
            'nome_contato.required' => 'O nome do contato é obrigatório',
            'nome_contato.max' => 'O nome do contato deve ter no máximo 255 caracteres',
        ];
    }
}
