<?php
namespace App\Livewire;
use App\Models\Compra;
use App\Models\Produto;
use App\Models\Venda;
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

    public function render()
    {
        $vendas = Venda::query()->whereBetween('data_movimentacao', [$this->inicio, $this->fim]);
        $compras = Compra::query()->whereBetween('data_movimentacao', [$this->inicio, $this->fim]);

        return view('livewire.relatorio-index', [
            'totalVendas' => (clone $vendas)->sum('valor_total'),
            'totalCompras' => (clone $compras)->sum('valor_total'),
            'quantidadeVendas' => (clone $vendas)->sum('quantidade'),
            'quantidadeCompras' => (clone $compras)->sum('quantidade'),
            'estoqueTotal' => Produto::sum('estoque'),
            'produtosBaixos' => Produto::with('categoriaRelacionada')->where('estoque', '<=', 5)->orderBy('estoque')->get(),
            'vendasRecentes' => (clone $vendas)->with('produto')->latest('data_movimentacao')->limit(5)->get(),
            'comprasRecentes' => (clone $compras)->with('produto')->latest('data_movimentacao')->limit(5)->get(),
        ]);
    }
}
