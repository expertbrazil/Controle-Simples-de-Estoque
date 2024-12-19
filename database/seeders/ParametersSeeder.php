<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametersSeeder extends Seeder
{
    public function run()
    {
        $parameters = [
            [
                'key' => 'company_name',
                'name' => 'Nome da Empresa',
                'value' => 'Minha Empresa',
                'group' => 'company',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Nome da empresa exibido no sistema',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'cnpj',
                'name' => 'CNPJ',
                'value' => null,
                'group' => 'company',
                'type' => 'text',
                'is_private' => false,
                'description' => 'CNPJ da empresa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'phone',
                'name' => 'Telefone',
                'value' => null,
                'group' => 'company',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Telefone da empresa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'email',
                'name' => 'E-mail',
                'value' => null,
                'group' => 'company',
                'type' => 'email',
                'is_private' => false,
                'description' => 'E-mail da empresa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'address',
                'name' => 'Endereço',
                'value' => null,
                'group' => 'company',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Endereço da empresa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'city',
                'name' => 'Cidade',
                'value' => null,
                'group' => 'company',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Cidade da empresa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'state',
                'name' => 'Estado',
                'value' => null,
                'group' => 'company',
                'type' => 'text',
                'is_private' => false,
                'description' => 'Estado da empresa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'zip_code',
                'name' => 'CEP',
                'value' => null,
                'group' => 'company',
                'type' => 'text',
                'is_private' => false,
                'description' => 'CEP da empresa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'system_logo',
                'name' => 'Logo do Sistema',
                'value' => null,
                'group' => 'system',
                'type' => 'image',
                'is_private' => false,
                'description' => 'Logo exibida no sistema',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('parameters')->insert($parameters);
    }
}
