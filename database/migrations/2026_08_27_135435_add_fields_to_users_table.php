<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cep')->nullable();
            $table->string('numero')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('complemento')->nullable();
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->decimal('saldo', 10, 2)->default(0);
            $table->string('foto')->nullable();
            $table->boolean('is_admin')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cep', 'numero', 'logradouro', 'bairro', 'cidade', 'estado',
                'complemento', 'telefone', 'data_nascimento', 'cpf',
                'saldo', 'foto', 'is_admin',
            ]);
        });
    }
};
