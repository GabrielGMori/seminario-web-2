<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Eletrônicos', 'descricao' => 'Produtos eletrônicos e acessórios.'],
            ['nome' => 'Informática', 'descricao' => 'Computadores e periféricos.'],
            ['nome' => 'Acessórios', 'descricao' => 'Acessórios para uso diário.'],
            ['nome' => 'Áudio', 'descricao' => 'Fones, caixas de som e equipamentos de áudio.'],
            ['nome' => 'Telefonia', 'descricao' => 'Celulares e acessórios para telefonia.'],
            ['nome' => 'Casa e Escritório', 'descricao' => 'Itens para casa e ambiente de trabalho.'],
            ['nome' => 'Games', 'descricao' => 'Consoles, controles e acessórios para jogos.'],
            ['nome' => 'Armazenamento', 'descricao' => 'Dispositivos para guardar e transportar arquivos.'],
            ['nome' => 'Monitores', 'descricao' => 'Monitores e acessórios de vídeo.'],
            ['nome' => 'Energia', 'descricao' => 'Carregadores, fontes e acessórios de energia.'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::withTrashed()->updateOrCreate(
                ['nome' => $categoria['nome']],
                ['descricao' => $categoria['descricao'], 'deleted_at' => null],
            );
        }
    }
}
