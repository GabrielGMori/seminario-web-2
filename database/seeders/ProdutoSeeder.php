<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
            [
                'name' => 'Fone Bluetooth',
                'categoria' => 'Eletrônicos',
                'estoque' => 25,
                'preco_venda' => 89.90,
            ],
            [
                'name' => 'Teclado USB',
                'categoria' => 'Informática',
                'estoque' => 18,
                'preco_venda' => 129.90,
            ],
            [
                'name' => 'Mouse sem fio',
                'categoria' => 'Informática',
                'estoque' => 30,
                'preco_venda' => 59.90,
            ],
            [
                'name' => 'Cabo USB-C',
                'categoria' => 'Acessórios',
                'estoque' => 40,
                'preco_venda' => 19.90,
            ],
            [
                'name' => 'Smart TV 43 polegadas',
                'categoria' => 'Eletrônicos',
                'estoque' => 8,
                'preco_venda' => 1899.90,
            ],
            [
                'name' => 'Webcam HD',
                'categoria' => 'Eletrônicos',
                'estoque' => 14,
                'preco_venda' => 249.90,
            ],
            [
                'name' => 'Notebook 15 polegadas',
                'categoria' => 'Informática',
                'estoque' => 7,
                'preco_venda' => 3299.90,
            ],
            [
                'name' => 'Teclado mecânico',
                'categoria' => 'Informática',
                'estoque' => 12,
                'preco_venda' => 249.90,
            ],
            [
                'name' => 'Fone de ouvido com fio',
                'categoria' => 'Áudio',
                'estoque' => 22,
                'preco_venda' => 49.90,
            ],
            [
                'name' => 'Caixa de som portátil',
                'categoria' => 'Áudio',
                'estoque' => 16,
                'preco_venda' => 179.90,
            ],
            [
                'name' => 'Smartphone 128 GB',
                'categoria' => 'Telefonia',
                'estoque' => 9,
                'preco_venda' => 1499.90,
            ],
            [
                'name' => 'Capa para smartphone',
                'categoria' => 'Telefonia',
                'estoque' => 35,
                'preco_venda' => 39.90,
            ],
            [
                'name' => 'Luminária de mesa',
                'categoria' => 'Casa e Escritório',
                'estoque' => 18,
                'preco_venda' => 79.90,
            ],
            [
                'name' => 'Suporte para notebook',
                'categoria' => 'Casa e Escritório',
                'estoque' => 20,
                'preco_venda' => 89.90,
            ],
            [
                'name' => 'Controle sem fio',
                'categoria' => 'Games',
                'estoque' => 11,
                'preco_venda' => 299.90,
            ],
            [
                'name' => 'Headset gamer',
                'categoria' => 'Games',
                'estoque' => 13,
                'preco_venda' => 219.90,
            ],
            [
                'name' => 'SSD externo 1 TB',
                'categoria' => 'Armazenamento',
                'estoque' => 10,
                'preco_venda' => 499.90,
            ],
            [
                'name' => 'Pen drive 64 GB',
                'categoria' => 'Armazenamento',
                'estoque' => 28,
                'preco_venda' => 39.90,
            ],
            [
                'name' => 'Monitor Full HD 24 polegadas',
                'categoria' => 'Monitores',
                'estoque' => 6,
                'preco_venda' => 899.90,
            ],
            [
                'name' => 'Suporte articulado para monitor',
                'categoria' => 'Monitores',
                'estoque' => 9,
                'preco_venda' => 119.90,
            ],
            [
                'name' => 'Carregador USB-C',
                'categoria' => 'Energia',
                'estoque' => 24,
                'preco_venda' => 79.90,
            ],
            [
                'name' => 'Filtro de linha',
                'categoria' => 'Energia',
                'estoque' => 17,
                'preco_venda' => 49.90,
            ],
            [
                'name' => 'Mouse pad',
                'categoria' => 'Acessórios',
                'estoque' => 32,
                'preco_venda' => 29.90,
            ],
            [
                'name' => 'Hub USB 4 portas',
                'categoria' => 'Acessórios',
                'estoque' => 15,
                'preco_venda' => 69.90,
            ],
            [
                'name' => 'Mouse com fio',
                'categoria' => 'Informática',
                'estoque' => 26,
                'preco_venda' => 39.90,
            ],
        ];

        foreach ($produtos as $produto) {
            $categoria = Categoria::where('nome', $produto['categoria'])->firstOrFail();
            unset($produto['categoria']);
            $produto['preco_compra'] = round($produto['preco_venda'] * 0.75, 2);
            $produto['categoria_id'] = $categoria->id;

            Produto::updateOrCreate(
                ['name' => $produto['name']],
                $produto
            );
        }
    }
}
