<?php

namespace Database\Seeders;

use App\Models\PriceList;
use Illuminate\Database\Seeder;

class PriceListSeeder extends Seeder
{
    public function run()
    {
        $lists = [
            [
                'name' => 'Lista Distribuidor Padrão',
                'markup_percentage' => 30.00,
                'type' => 'distributor',
                'is_active' => true
            ],
            [
                'name' => 'Lista Distribuidor Premium',
                'markup_percentage' => 40.00,
                'type' => 'distributor',
                'is_active' => true
            ],
            [
                'name' => 'Lista Consumidor Padrão',
                'markup_percentage' => 60.00,
                'type' => 'consumer',
                'is_active' => true
            ],
            [
                'name' => 'Lista Consumidor Premium',
                'markup_percentage' => 80.00,
                'type' => 'consumer',
                'is_active' => true
            ]
        ];

        foreach ($lists as $list) {
            PriceList::create($list);
        }
    }
}
