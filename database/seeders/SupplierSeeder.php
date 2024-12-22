<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $suppliers = [
            [
                'tipo_pessoa' => 'J',
                'nome' => 'Distribuidora Esportiva',
                'razao_social' => 'Distribuidora Esportiva LTDA',
                'documento' => '12345678000190',
                'nome_contato' => 'João Silva',
                'status' => true
            ],
            [
                'tipo_pessoa' => 'J',
                'nome' => 'Atacado Sports',
                'razao_social' => 'Atacado Sports LTDA',
                'documento' => '98765432000190',
                'nome_contato' => 'Maria Santos',
                'status' => true
            ],
            [
                'tipo_pessoa' => 'J',
                'nome' => 'Importadora BR',
                'razao_social' => 'Importadora BR LTDA',
                'documento' => '45678912000190',
                'nome_contato' => 'Pedro Oliveira',
                'status' => true
            ]
        ];

        foreach ($suppliers as $supplier) {
            \App\Models\Supplier::create($supplier);
        }
    }
}
