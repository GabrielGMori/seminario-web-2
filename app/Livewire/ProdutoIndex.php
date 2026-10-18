<?php

namespace App\Livewire;

use App\Models\Produto;
use Livewire\Component;
use Livewire\WithPagination;

class ProdutoIndex extends Component
{
    use WithPagination;

    public string $filtroNome = '';
    public string $filtroIdentificador = '';
    public string $filtroEstoque = '';
    public string $filtroCategoria = '';

    public function updatedFiltroNome(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroIdentificador(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstoque(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroCategoria(): void
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
            ->when($this->filtroNome !== '', fn ($query) => $query->where('name', 'like', '%' . $this->filtroNome . '%'))
            ->when($this->filtroIdentificador !== '', fn ($query) => $query->where('identificador', 'like', '%' . $this->filtroIdentificador . '%'))
            ->when($this->filtroEstoque !== '', fn ($query) => $query->where('estoque', $this->filtroEstoque))
            ->when($this->filtroCategoria !== '', fn ($query) => $query->where('categoria', 'like', '%' . $this->filtroCategoria . '%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.produto-index', compact('produtos'));
    }
}
