<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Inserindo marcas (brands)
        DB::table('brands')->insert([
            ['name' => 'Nike', 'description' => 'Produtos esportivos de alta qualidade', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Adidas', 'description' => 'Marca alemã de artigos esportivos', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Puma', 'description' => 'Equipamentos esportivos profissionais', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Under Armour', 'description' => 'Roupas e acessórios esportivos', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Asics', 'description' => 'Especializada em calçados esportivos', 'status' => 1, 'created_at' => now(), 'updated_at' => now()]
        ]);

        // Inserindo categorias (categories)
        $calcados = DB::table('categories')->insertGetId([
            'name' => 'Calçados',
            'description' => 'Todos os tipos de calçados',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roupas = DB::table('categories')->insertGetId([
            'name' => 'Roupas',
            'description' => 'Vestuário em geral',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $acessorios = DB::table('categories')->insertGetId([
            'name' => 'Acessórios',
            'description' => 'Acessórios diversos',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Subcategorias
        DB::table('categories')->insert([
            [
                'name' => 'Tênis de Corrida',
                'description' => 'Calçados específicos para corrida',
                'parent_id' => $calcados,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tênis Casual',
                'description' => 'Calçados para uso diário',
                'parent_id' => $calcados,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Camisetas',
                'description' => 'Camisetas e regatas',
                'parent_id' => $roupas,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Shorts',
                'description' => 'Shorts e bermudas',
                'parent_id' => $roupas,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Meias',
                'description' => 'Meias esportivas',
                'parent_id' => $acessorios,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bonés',
                'description' => 'Bonés e chapéus',
                'parent_id' => $acessorios,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Cliente Pessoa Física
        DB::table('suppliers')->insert([
            'status' => 1,
            'tipo_pessoa' => 'F',
            'nome' => 'João Silva',
            'documento' => '123.456.789-00',
            'phone' => '(11) 98765-4321',
            'email' => 'joao@email.com',
            'cep' => '12345-678',
            'rua' => 'Rua das Flores',
            'numero' => '123',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'uf' => 'SP',
            'nome_contato' => 'João Silva',
            'flag' => '["cliente"]',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Cliente Pessoa Jurídica
        DB::table('suppliers')->insert([
            'status' => 1,
            'tipo_pessoa' => 'J',
            'nome' => 'Esportes LTDA',
            'razao_social' => 'Esportes e Cia LTDA',
            'documento' => '12.345.678/0001-90',
            'phone' => '(11) 3333-4444',
            'email' => 'contato@esportes.com',
            'cep' => '12345-678',
            'rua' => 'Av Comercial',
            'numero' => '1000',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'uf' => 'SP',
            'nome_contato' => 'Maria Santos',
            'flag' => '["cliente","revendedor"]',
            'inscricao_estadual' => '123456789',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Fornecedor
        DB::table('suppliers')->insert([
            'status' => 1,
            'tipo_pessoa' => 'J',
            'nome' => 'Distribuidora XYZ',
            'razao_social' => 'Distribuidora XYZ LTDA',
            'documento' => '98.765.432/0001-10',
            'phone' => '(11) 2222-3333',
            'email' => 'contato@xyz.com',
            'cep' => '12345-678',
            'rua' => 'Rua Industrial',
            'numero' => '500',
            'bairro' => 'Distrito Industrial',
            'cidade' => 'São Paulo',
            'uf' => 'SP',
            'nome_contato' => 'Carlos Oliveira',
            'flag' => '["fornecedor"]',
            'inscricao_estadual' => '987654321',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Fornecedor e Revendedor
        DB::table('suppliers')->insert([
            'status' => 1,
            'tipo_pessoa' => 'J',
            'nome' => 'Mega Sports',
            'razao_social' => 'Mega Sports Comercio LTDA',
            'documento' => '45.678.901/0001-23',
            'phone' => '(11) 4444-5555',
            'email' => 'contato@megasports.com',
            'cep' => '12345-678',
            'rua' => 'Av Principal',
            'numero' => '200',
            'bairro' => 'Jardim Europa',
            'cidade' => 'São Paulo',
            'uf' => 'SP',
            'nome_contato' => 'Ana Paula',
            'flag' => '["fornecedor","revendedor"]',
            'inscricao_estadual' => '456789012',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
