<?php

namespace App\Livewire;

use App\Models\Produto;
use Illuminate\Database\QueryException;
use Livewire\Component;

class ProdutoIndex extends Component
{
    public string $filtroNome = '';
    public string $filtroIdentificador = '';
    public string $filtroEstoque = '';
    public string $filtroCategoria = '';
    public int $porPagina = 10;

    public function excluir(int $id): void
    {
        $produto = Produto::findOrFail($id);

        try {
            $produto->delete();

            session()->flash('status', 'Produto excluído com sucesso!');
        } catch (QueryException $exception) {
            report($exception);

            session()->flash(
                'status',
                'Não foi possível excluir o produto. Verifique se o Soft Delete está configurado corretamente.'
            );
        }
    }

    public function render()
    {
        $produtos = Produto::query()
            ->when(
                $this->filtroNome !== '',
                fn ($query) => $query->where('name', 'like', '%' . $this->filtroNome . '%')
            )
            ->when(
                $this->filtroIdentificador !== '',
                fn ($query) => $query->where('identificador', 'like', '%' . $this->filtroIdentificador . '%')
            )
            ->when(
                $this->filtroEstoque !== '',
                fn ($query) => $query->where('estoque', $this->filtroEstoque)
            )
            ->when(
                $this->filtroCategoria !== '',
                fn ($query) => $query->where('categoria', 'like', '%' . $this->filtroCategoria . '%')
            )
            ->orderBy('name')
            ->paginate($this->porPagina, ['*'], 'page', request()->integer('page', 1));

        return view('livewire.produto-index', compact('produtos'));
    }
}
