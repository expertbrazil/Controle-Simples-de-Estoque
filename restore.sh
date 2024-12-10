#!/bin/bash

# Verificar se foi fornecido o nome do arquivo de backup
if [ -z "$1" ]; then
    echo "Por favor, forneça o nome do arquivo de backup."
    echo "Exemplo: ./restore.sh backup_2024-01-10_23-00-00.sql"
    echo "\nBackups disponíveis:"
    ls -l storage/app/backups/
    exit 1
fi

# Verificar se o arquivo existe
BACKUP_FILE="storage/app/backups/$1"
if [ ! -f "$BACKUP_FILE" ]; then
    echo "Arquivo de backup não encontrado: $BACKUP_FILE"
    exit 1
fi

# Configurações do banco de dados do .env
DB_DATABASE=$(php artisan tinker --execute="echo config('database.connections.mysql.database');" | grep -v ">>>" | tr -d '\n')
DB_USERNAME=$(php artisan tinker --execute="echo config('database.connections.mysql.username');" | grep -v ">>>" | tr -d '\n')
DB_PASSWORD=$(php artisan tinker --execute="echo config('database.connections.mysql.password');" | grep -v ">>>" | tr -d '\n')
DB_HOST=$(php artisan tinker --execute="echo config('database.connections.mysql.host');" | grep -v ">>>" | tr -d '\n')
DB_PORT=$(php artisan tinker --execute="echo config('database.connections.mysql.port');" | grep -v ">>>" | tr -d '\n')

# Restaurar o backup
echo "Restaurando backup: $BACKUP_FILE"
export MYSQL_PWD="$DB_PASSWORD"
/Applications/MAMP/Library/bin/mysql80/bin/mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" "$DB_DATABASE" < "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "Backup restaurado com sucesso!"
else
    echo "Erro ao restaurar o backup."
    exit 1
fi
