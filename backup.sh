#!/bin/bash

# Criar backup do banco de dados
php artisan db:backup

# Mostrar os backups existentes
echo "Backups disponíveis:"
ls -l storage/app/backups/
