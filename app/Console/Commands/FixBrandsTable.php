<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixBrandsTable extends Command
{
    protected $signature = 'fix:brands-table';
    protected $description = 'Fix brands table structure';

    public function handle()
    {
        $this->info('Fixing brands table...');

        try {
            // Drop the table if it exists
            if (Schema::hasTable('brands')) {
                Schema::drop('brands');
            }

            // Create the table with correct structure
            DB::statement('
                CREATE TABLE brands (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    description TEXT NULL,
                    status BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    deleted_at TIMESTAMP NULL
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            ');

            $this->info('Brands table fixed successfully!');
        } catch (\Exception $e) {
            $this->error('Error fixing brands table: ' . $e->getMessage());
        }
    }
}
