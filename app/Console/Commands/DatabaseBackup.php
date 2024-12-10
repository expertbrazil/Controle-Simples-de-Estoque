<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Create a backup of the database';

    public function handle()
    {
        // Configurações do banco de dados
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        // Nome do arquivo de backup
        $filename = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        // Criar diretório de backup se não existir
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        // Comando para criar o backup usando o MySQL do MAMP
        $command = sprintf(
            'export MYSQL_PWD="%s" && /Applications/MAMP/Library/bin/mysql80/bin/mysqldump -h%s -P%s -u%s %s > %s',
            $password,
            $host,
            $port,
            $username,
            $database,
            $path
        );

        // Executar o comando
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info('Backup criado com sucesso: ' . $filename);
            
            // Manter apenas os últimos 5 backups
            $backups = Storage::files('backups');
            if (count($backups) > 5) {
                rsort($backups); // Ordenar por data decrescente
                $oldBackups = array_slice($backups, 5);
                foreach ($oldBackups as $oldBackup) {
                    Storage::delete($oldBackup);
                }
            }
        } else {
            $this->error('Erro ao criar backup: ' . implode("\n", $output));
        }
    }
}
