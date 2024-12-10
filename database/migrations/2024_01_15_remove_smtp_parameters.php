<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Parameter;

return new class extends Migration
{
    public function up()
    {
        // Remove os parâmetros SMTP antigos
        Parameter::where('key', 'like', 'smtp_%')->delete();
        Parameter::where('key', 'like', 'mail_%')->delete();
    }

    public function down()
    {
        // Não é necessário restaurar os parâmetros antigos
    }
};
