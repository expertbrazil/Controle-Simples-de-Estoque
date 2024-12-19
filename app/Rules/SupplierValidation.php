<?php

namespace App\Rules;

use App\Models\Supplier;
use Illuminate\Validation\Rule;

class SupplierValidation
{
    public function rules($supplierId = null)
    {
        $rules = [
            'status' => 'boolean',
            'tipo_pessoa' => 'required|in:F,J',
            'nome' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('suppliers', 'documento')->ignore($supplierId)
            ],
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'cep' => 'nullable|string|max:10',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|size:2',
            'nome_contato' => 'required|string|max:255',
            'flag' => 'nullable|array',
            'flag.*' => 'string|in:cliente,fornecedor,revendedor',
            'usuario' => 'nullable|string|max:255',
            'senha' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:1000',
            'inscricao_estadual' => 'nullable|string|max:20',
            'inscricao_municipal' => 'nullable|string|max:20'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'status.boolean' => 'O status deve ser verdadeiro ou falso',
            'tipo_pessoa.required' => 'O tipo de pessoa é obrigatório',
            'tipo_pessoa.in' => 'O tipo de pessoa deve ser F (Física) ou J (Jurídica)',
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres',
            'razao_social.max' => 'A razão social deve ter no máximo 255 caracteres',
            'documento.required' => 'O CPF/CNPJ é obrigatório',
            'documento.max' => 'O documento deve ter no máximo 20 caracteres',
            'documento.unique' => 'Este CPF/CNPJ já está cadastrado',
            'phone.max' => 'O telefone deve ter no máximo 20 caracteres',
            'whatsapp.max' => 'O WhatsApp deve ter no máximo 20 caracteres',
            'email.email' => 'O e-mail informado não é válido',
            'email.max' => 'O e-mail deve ter no máximo 255 caracteres',
            'cep.max' => 'O CEP deve ter no máximo 10 caracteres',
            'rua.max' => 'A rua deve ter no máximo 255 caracteres',
            'numero.max' => 'O número deve ter no máximo 10 caracteres',
            'complemento.max' => 'O complemento deve ter no máximo 255 caracteres',
            'bairro.max' => 'O bairro deve ter no máximo 255 caracteres',
            'cidade.max' => 'A cidade deve ter no máximo 100 caracteres',
            'uf.size' => 'O UF deve ter exatamente 2 caracteres',
            'nome_contato.required' => 'O nome do contato é obrigatório',
            'nome_contato.max' => 'O nome do contato deve ter no máximo 255 caracteres',
            'flag.array' => 'As flags devem ser um array',
            'flag.*.in' => 'Flag inválida. Use: cliente, fornecedor ou revendedor',
            'usuario.max' => 'O usuário deve ter no máximo 255 caracteres',
            'senha.max' => 'A senha deve ter no máximo 255 caracteres',
            'observacoes.max' => 'As observações devem ter no máximo 1000 caracteres',
            'inscricao_estadual.max' => 'A inscrição estadual deve ter no máximo 20 caracteres',
            'inscricao_municipal.max' => 'A inscrição municipal deve ter no máximo 20 caracteres'
        ];
    }
}
