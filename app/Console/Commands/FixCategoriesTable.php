<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixCategoriesTable extends Command
{
    protected $signature = 'fix:categories-table';
    protected $description = 'Fix categories table structure';

    public function handle()
    {
        $this->info('Fixing categories table...');

        try {
            if (!Schema::hasColumn('categories', 'parent_id')) {
                DB::statement('
                    ALTER TABLE categories 
                    ADD COLUMN parent_id BIGINT UNSIGNED NULL AFTER id,
                    ADD CONSTRAINT categories_parent_id_foreign 
                    FOREIGN KEY (parent_id) 
                    REFERENCES categories(id) 
                    ON DELETE SET NULL
                ');
            }

            $this->info('Categories table fixed successfully!');
        } catch (\Exception $e) {
            $this->error('Error fixing categories table: ' . $e->getMessage());
        }
    }
}
