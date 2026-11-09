<?php

namespace App\Livewire;

use App\Http\Requests\MovimentacaoRequest;
use App\Models\Compra;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MovimentacaoForm extends Component
{
    private const MAX_TOTAL_CENTAVOS = 9_999_999_999;

    #[Locked]
    public string $tipo = 'venda';

    public string $data = '';

    public string $precoTotal = '0.00';

    public array $produtos = [];

    public function mount(string $tipo): void
    {
        abort_unless(in_array($tipo, ['venda', 'compra'], true), 404);

        $this->tipo = $tipo;
        $this->data = now()->toDateString();
        $this->adicionarItem();
    }

    public function adicionarItem(): void
    {
        $this->produtos[] = [
            'produto_id' => '',
            'quantidade' => 1,
        ];

        $this->atualizarPrecoTotal();
    }

    public function removerItem(int $index): void
    {
        unset($this->produtos[$index]);
        $this->produtos = array_values($this->produtos);

        if ($this->produtos === []) {
            $this->adicionarItem();
        }

        $this->atualizarPrecoTotal();
    }

    public function updatedProdutos(): void
    {
        $this->atualizarPrecoTotal();
    }

    private function atualizarPrecoTotal(): void
    {
        $this->precoTotal = $this->calcularPrecoTotal();
    }

    private function calcularPrecoTotal(): string
    {
        $ids = collect($this->produtos)
            ->pluck('produto_id')
            ->filter(fn ($id) => is_numeric($id))
            ->unique()
            ->values();

        $colunaPreco = $this->tipo === 'venda' ? 'preco_venda' : 'preco_compra';
        $precos = Produto::query()
            ->whereIn('id', $ids)
            ->pluck($colunaPreco, 'id');

        $totalEmCentavos = 0;
        foreach ($this->produtos as $item) {
            $id = $item['produto_id'] ?? null;
            $quantidade = max(0, (int) ($item['quantidade'] ?? 0));

            if (! is_numeric($id) || ! $precos->has((int) $id)) {
                continue;
            }

            if ($quantidade === 0) {
                continue;
            }

            $precoEmCentavos = (int) round((float) $precos->get((int) $id) * 100);
            if ($precoEmCentavos > intdiv(self::MAX_TOTAL_CENTAVOS - $totalEmCentavos, $quantidade)) {
                return number_format((self::MAX_TOTAL_CENTAVOS + 1) / 100, 2, '.', '');
            }

            $totalEmCentavos += $precoEmCentavos * $quantidade;
        }

        return number_format($totalEmCentavos / 100, 2, '.', '');
    }

    public function salvar(): void
    {
        $request = new MovimentacaoRequest;
        abort_unless($request->authorize(), 403);
        $dados = $this->validate($request->rules());

        DB::transaction(function () use ($dados): void {
            $quantidades = collect($dados['produtos'])
                ->groupBy('produto_id')
                ->map(fn ($items) => $items->sum(fn ($item) => (int) $item['quantidade']));
            $produtos = Produto::query()
                ->whereIn('id', $quantidades->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (Produto $produto) => (int) $produto->id);

            foreach ($quantidades as $produtoId => $quantidade) {
                $produto = $produtos->get((int) $produtoId);

                if ($produto === null) {
                    $index = collect($dados['produtos'])->search(
                        fn ($item) => (int) $item['produto_id'] === (int) $produtoId
                    );

                    throw ValidationException::withMessages([
                        "produtos.$index.produto_id" => __('ui.product_unavailable'),
                    ]);
                }

                if ($this->tipo === 'venda' && $produto->estoque < $quantidade) {
                    $index = collect($dados['produtos'])->search(
                        fn ($item) => (int) $item['produto_id'] === (int) $produtoId
                    );

                    throw ValidationException::withMessages([
                        "produtos.$index.quantidade" => __('ui.insufficient_stock_sale'),
                    ]);
                }
            }

            $classe = $this->tipo === 'venda' ? Venda::class : Compra::class;
            $movimentacao = $classe::create([
                'data' => $dados['data'],
                'preco' => number_format((float) $dados['precoTotal'], 2, '.', ''),
                'user_id' => auth()->id(),
            ]);

            foreach ($quantidades as $produtoId => $quantidade) {
                $produto = $produtos->find((int) $produtoId);

                if ($this->tipo === 'venda') {
                    $produto->decrement('estoque', $quantidade);
                } else {
                    $produto->increment('estoque', $quantidade);
                }

                $movimentacao->produtos()->attach($produtoId, [
                    'quantidade' => $quantidade,
                ]);
            }
        });

        session()->flash('status', __('ui.movement_registered', [
            'type' => __($this->tipo === 'venda' ? 'ui.register_sale' : 'ui.register_purchase'),
        ]));
        $this->redirectRoute($this->tipo === 'venda' ? 'vendas.index' : 'compras.index');
    }

    public function render()
    {
        return view('livewire.movimentacao-form', [
            'titulo' => __($this->tipo === 'venda' ? 'navigation.sales' : 'navigation.purchases'),
            'produtosDisponiveis' => Produto::orderBy('name')->get(),
            'precoEsperado' => $this->calcularPrecoTotal(),
            'colunaPreco' => $this->tipo === 'venda' ? 'preco_venda' : 'preco_compra',
        ]);
    }
}
