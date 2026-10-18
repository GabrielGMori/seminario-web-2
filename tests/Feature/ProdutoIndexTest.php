<?php

use App\Livewire\ProdutoIndex;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('filtra produtos por atributos e combina os filtros informados', function () {
    Produto::create([
        'name' => 'Camiseta Azul',
        'identificador' => 'CAM-001',
        'estoque' => 12,
        'categoria' => 'Vestuário',
    ]);

    Produto::create([
        'name' => 'Camiseta Vermelha',
        'identificador' => 'CAM-002',
        'estoque' => 8,
        'categoria' => 'Vestuário',
    ]);

    Produto::create([
        'name' => 'Caneca Azul',
        'identificador' => 'CAN-001',
        'estoque' => 12,
        'categoria' => 'Casa',
    ]);

    Livewire::test(ProdutoIndex::class)
        ->set('filtroNome', 'Camiseta')
        ->set('filtroIdentificador', 'CAM-001')
        ->set('filtroEstoque', '12')
        ->set('filtroCategoria', 'Vestu')
        ->assertSee('CAM-001')
        ->assertDontSee('CAM-002')
        ->assertDontSee('CAN-001');
});
