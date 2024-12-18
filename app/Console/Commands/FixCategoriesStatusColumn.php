<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixCategoriesStatusColumn extends Command
{
    protected $signature = 'fix:categories-status';
    protected $description = 'Fix categories table status column';

    public function handle()
    {
        $this->info('Fixing categories table status column...');

        try {
            if (Schema::hasColumn('categories', 'active') && !Schema::hasColumn('categories', 'status')) {
                // Primeiro adiciona a nova coluna
                DB::statement('ALTER TABLE categories ADD COLUMN status BOOLEAN DEFAULT TRUE AFTER description');
                
                // Copia os dados de active para status
                DB::statement('UPDATE categories SET status = active');
                
                // Remove a coluna antiga
                DB::statement('ALTER TABLE categories DROP COLUMN active');
                
                $this->info('Categories table status column fixed successfully!');
            } else {
                $this->info('No changes needed for categories table.');
            }
        } catch (\Exception $e) {
            $this->error('Error fixing categories table: ' . $e->getMessage());
        }
    }
}
