<?php

namespace App\Livewire;

use App\Models\Compra;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RelatorioIndex extends Component
{
    public string $inicio = '';

    public string $fim = '';

    public function mount(): void
    {
        $this->inicio = now()->startOfMonth()->toDateString();
        $this->fim = now()->toDateString();
    }

    public function updated(string $property): void
    {
        if (! in_array($property, ['inicio', 'fim'], true)) {
            return;
        }

        $this->validateOnly($property, [
            $property => ['required', 'date'],
        ]);

        if ($this->inicio > $this->fim) {
            $this->resetErrorBag('fim');
            $this->addError('fim', __('ui.end_date_after_start'));

            return;
        }

        $this->resetValidation('fim');
    }

    public function render()
    {
        return view('livewire.relatorio-index', [
            'totalVendas' => Venda::query()
                ->whereDate('data', '>=', $this->inicio)
                ->whereDate('data', '<=', $this->fim)
                ->sum('preco'),
            'totalCompras' => Compra::query()
                ->whereDate('data', '>=', $this->inicio)
                ->whereDate('data', '<=', $this->fim)
                ->sum('preco'),
            'quantidadeVendas' => DB::table('venda_produto')
                ->join('vendas', 'vendas.id', '=', 'venda_produto.venda_id')
                ->whereNull('vendas.deleted_at')
                ->whereDate('vendas.data', '>=', $this->inicio)
                ->whereDate('vendas.data', '<=', $this->fim)
                ->sum('venda_produto.quantidade'),
            'quantidadeCompras' => DB::table('compra_produto')
                ->join('compras', 'compras.id', '=', 'compra_produto.compra_id')
                ->whereNull('compras.deleted_at')
                ->whereDate('compras.data', '>=', $this->inicio)
                ->whereDate('compras.data', '<=', $this->fim)
                ->sum('compra_produto.quantidade'),
            'estoqueTotal' => Produto::sum('estoque'),
            'produtosBaixos' => Produto::with('categoria')
                ->where('estoque', '<=', 5)
                ->orderBy('estoque')
                ->get(),
            'vendasRecentes' => Venda::query()
                ->with(['produtos' => fn ($query) => $query->withTrashed()])
                ->whereDate('data', '>=', $this->inicio)
                ->whereDate('data', '<=', $this->fim)
                ->latest('data')
                ->limit(5)
                ->get(),
            'comprasRecentes' => Compra::query()
                ->with(['produtos' => fn ($query) => $query->withTrashed()])
                ->whereDate('data', '>=', $this->inicio)
                ->whereDate('data', '<=', $this->fim)
                ->latest('data')
                ->limit(5)
                ->get(),
        ]);
    }
}
