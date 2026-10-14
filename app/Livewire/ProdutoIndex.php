<?php

namespace App\Livewire;

use App\Models\Produto;
use Livewire\Component;
use Livewire\WithPagination;

class ProdutoIndex extends Component
{
    use WithPagination;

    public string $busca = '';

    public function updatedBusca(): void
    {
        $this->resetPage();
    }

    public function excluir(int $id): void
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        session()->flash('status', 'Produto excluído com sucesso!');
        $this->resetPage();
    }

    public function render()
    {
        $produtos = Produto::query()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->busca . '%')
                    ->orWhere('identificador', 'like', '%' . $this->busca . '%')
                    ->orWhere('categoria', 'like', '%' . $this->busca . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.produto-index', compact('produtos'));
    }
}
