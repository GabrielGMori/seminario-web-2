<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['vendas', 'compras'] as $tabela) {
            Schema::create($tabela, function (Blueprint $table) {
                $table->id();
                $table->foreignId('produto_id')->constrained('produtos')->restrictOnDelete();
                $table->unsignedInteger('quantidade');
                $table->decimal('valor_unitario', 10, 2);
                $table->decimal('valor_total', 12, 2);
                $table->date('data_movimentacao');
                $table->string('observacao')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
                $table->index('data_movimentacao');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
        Schema::dropIfExists('vendas');
    }
};
