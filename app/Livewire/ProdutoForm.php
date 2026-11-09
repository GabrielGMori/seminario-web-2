<?php

namespace App\Livewire;

use App\Http\Requests\ProdutoRequest;
use App\Models\Categoria;
use App\Models\Produto;
use Livewire\Component;

class ProdutoForm extends Component
{
    public ?int $produtoId = null;

    public string $name = '';

    public string $estoque = '';

    public string $preco_compra = '';

    public string $preco_venda = '';

    public string $categoria_id = '';

    public function mount(?int $produto = null): void
    {
        if ($produto === null) {
            return;
        }

        $registro = Produto::findOrFail($produto);
        $this->produtoId = $registro->id;
        $this->name = $registro->name;
        $this->estoque = (string) $registro->estoque;
        $this->preco_compra = (string) $registro->preco_compra;
        $this->preco_venda = (string) $registro->preco_venda;
        $this->categoria_id = (string) ($registro->categoria_id ?? '');
    }

    public function salvar()
    {
        $request = new ProdutoRequest;
        abort_unless($request->authorize(), 403);
        $dados = $this->validate($request->rulesFor());

        if ($this->produtoId !== null) {
            Produto::findOrFail($this->produtoId)->update($dados);
            session()->flash('status', __('ui.product_updated'));
        } else {
            Produto::create($dados);
            session()->flash('status', __('ui.product_created'));
        }

        return $this->redirectRoute('produtos.index');
    }

    public function render()
    {
        return view('livewire.produto-form', [
            'editando' => $this->produtoId !== null,
            'categorias' => Categoria::orderBy('nome')->get(),
        ]);
    }
}
