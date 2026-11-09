<?php

namespace App\Livewire;

use App\Models\Produto;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProdutoIndex extends Component
{
    use WithPagination;

    public string $filtroNome = '';

    public string $filtroEstoque = '';

    public string $precoCompraMinimo = '';

    public string $precoCompraMaximo = '';

    public string $precoVendaMinimo = '';

    public string $precoVendaMaximo = '';

    public string $filtroCategoria = '';

    public int $porPagina = 10;

    public bool $mostrarLixeira = false;

    public function updated(string $property): void
    {
        if (in_array($property, [
            'filtroNome',
            'filtroEstoque',
            'precoCompraMinimo',
            'precoCompraMaximo',
            'precoVendaMinimo',
            'precoVendaMaximo',
            'filtroCategoria',
        ], true)) {
            $this->resetPage();
        }

        if ($property === 'porPagina') {
            $this->porPagina = in_array($this->porPagina, [10, 25, 50], true) ? $this->porPagina : 10;
            $this->resetPage();
        }
    }

    public function excluir(int $id): void
    {
        Produto::findOrFail($id)->delete();

        session()->flash('status', __('ui.product_deleted'));
    }

    public function alternarLixeira(): void
    {
        $this->mostrarLixeira = ! $this->mostrarLixeira;
        $this->resetPage();
    }

    public function restaurar(int $id): void
    {
        Produto::onlyTrashed()->findOrFail($id)->restore();

        session()->flash('status', __('ui.product_restored'));
    }

    public function excluirPermanentemente(int $id): void
    {
        $this->resetErrorBag('geral');
        $produto = Produto::onlyTrashed()->findOrFail($id);
        $possuiMovimentacoes = DB::table('venda_produto')->where('produto_id', $id)->exists()
            || DB::table('compra_produto')->where('produto_id', $id)->exists();

        if ($possuiMovimentacoes) {
            $this->addError('geral', __('ui.product_permanent_delete_blocked'));

            return;
        }

        $produto->forceDelete();
        session()->flash('status', __('ui.product_permanently_deleted'));
    }

    public function limparFiltros(): void
    {
        $this->filtroNome = '';
        $this->filtroEstoque = '';
        $this->precoCompraMinimo = '';
        $this->precoCompraMaximo = '';
        $this->precoVendaMinimo = '';
        $this->precoVendaMaximo = '';
        $this->filtroCategoria = '';

        $this->resetPage();
    }

    public function render()
    {
        $consulta = Produto::query()->with('categoria');

        if ($this->mostrarLixeira) {
            $consulta->onlyTrashed();
        }

        $produtos = $consulta
            ->when($this->filtroNome !== '', fn ($query) => $query->where('name', 'like', '%'.$this->filtroNome.'%'))
            ->when($this->filtroEstoque !== '' && is_numeric($this->filtroEstoque), fn ($query) => $query->where('estoque', $this->filtroEstoque))
            ->when($this->precoCompraMinimo !== '' && is_numeric($this->precoCompraMinimo), fn ($query) => $query->where('preco_compra', '>=', $this->precoCompraMinimo))
            ->when($this->precoCompraMaximo !== '' && is_numeric($this->precoCompraMaximo), fn ($query) => $query->where('preco_compra', '<=', $this->precoCompraMaximo))
            ->when($this->precoVendaMinimo !== '' && is_numeric($this->precoVendaMinimo), fn ($query) => $query->where('preco_venda', '>=', $this->precoVendaMinimo))
            ->when($this->precoVendaMaximo !== '' && is_numeric($this->precoVendaMaximo), fn ($query) => $query->where('preco_venda', '<=', $this->precoVendaMaximo))
            ->when(
                $this->filtroCategoria !== '',
                fn ($query) => $query->whereHas('categoria', fn ($categoria) => $categoria->where('nome', 'like', '%'.$this->filtroCategoria.'%'))
            )
            ->orderBy('name')
            ->paginate($this->porPagina);

        return view('livewire.produto-index', [
            'produtos' => $produtos,
            'totalLixeira' => Produto::onlyTrashed()->count(),
        ]);
    }
}
