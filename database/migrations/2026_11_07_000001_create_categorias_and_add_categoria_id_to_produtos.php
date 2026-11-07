<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->after('categoria')
                ->constrained('categorias')->nullOnDelete();
        });

        DB::table('produtos')->select('categoria')->distinct()->whereNotNull('categoria')
            ->orderBy('categoria')->get()->each(function ($produto) {
                $id = DB::table('categorias')->insertGetId([
                    'nome' => $produto->categoria,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                DB::table('produtos')->where('categoria', $produto->categoria)->update(['categoria_id' => $id]);
            });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('categoria_id');
        });
        Schema::dropIfExists('categorias');
    }
};
