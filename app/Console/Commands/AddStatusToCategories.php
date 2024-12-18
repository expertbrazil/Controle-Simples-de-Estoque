<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStatusToCategories extends Command
{
    protected $signature = 'fix:add-status-to-categories';
    protected $description = 'Add status column to categories table';

    public function handle()
    {
        $this->info('Adding status column to categories table...');

        try {
            if (!Schema::hasColumn('categories', 'status')) {
                DB::statement('ALTER TABLE categories ADD COLUMN status BOOLEAN DEFAULT TRUE AFTER description');
                $this->info('Status column added successfully!');
            } else {
                $this->info('Status column already exists.');
            }
        } catch (\Exception $e) {
            $this->error('Error adding status column: ' . $e->getMessage());
        }
    }
}
