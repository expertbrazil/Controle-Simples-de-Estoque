<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDatabase extends Command
{
    protected $signature = 'db:reset';
    protected $description = 'Reset the entire database';

    public function handle()
    {
        // Desabilitar verificação de chave estrangeira
        Schema::disableForeignKeyConstraints();

        // Obter todas as tabelas
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = '".env('DB_DATABASE')."'");

        foreach ($tables as $table) {
            $tableName = $table->table_name;
            Schema::dropIfExists($tableName);
        }

        // Reabilitar verificação de chave estrangeira
        Schema::enableForeignKeyConstraints();

        $this->info('Database reset successfully.');
        
        // Rodar migrações
        $this->call('migrate:fresh', ['--seed' => true]);
    }
}
