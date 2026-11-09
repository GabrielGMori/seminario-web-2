<?php

namespace App\Livewire;

use App\Models\Compra;
use App\Models\Produto;
use App\Models\Venda;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;

class MovimentacaoIndex extends Component
{
    use WithPagination;

    #[Locked]
    public string $tipo = 'venda';
    public string $busca = '';
    public int $porPagina = 10;
    public bool $mostrarLixeira = false;

    public function mount(string $tipo): void
    {
        abort_unless(in_array($tipo, ['venda', 'compra'], true), 404);
        $this->tipo = $tipo;
    }

    private function tipoLabel(): string
    {
        return __($this->tipo === 'venda' ? 'ui.sale_noun' : 'ui.purchase_noun');
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['busca', 'porPagina'], true)) {
            if ($property === 'porPagina') {
                $this->porPagina = in_array($this->porPagina, [10, 25, 50], true) ? $this->porPagina : 10;
            }

            $this->resetPage();
        }
    }

    public function alternarLixeira(): void
    {
        $this->mostrarLixeira = ! $this->mostrarLixeira;
        $this->resetPage();
    }

    public function excluir(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $movimentacao = $this->classeMovimentacao()::query()
                ->lockForUpdate()
                ->findOrFail($id);

            $this->ajustarEstoque($movimentacao, false);
            $movimentacao->delete();
        });

        session()->flash('status', __('ui.movement_trashed', ['type' => $this->tipoLabel()]));
    }

    public function restaurar(int $id): void
    {
        $this->resetErrorBag('movimentacao');

        DB::transaction(function () use ($id): void {
            $movimentacao = $this->classeMovimentacao()::onlyTrashed()
                ->lockForUpdate()
                ->findOrFail($id);

            $this->ajustarEstoque($movimentacao, true);
            $movimentacao->restore();
        });

        session()->flash('status', __('ui.movement_restored', ['type' => $this->tipoLabel()]));
    }

    public function excluirPermanentemente(int $id): void
    {
        $this->classeMovimentacao()::onlyTrashed()->findOrFail($id)->forceDelete();

        session()->flash('status', __('ui.movement_permanently_deleted', ['type' => $this->tipoLabel()]));
    }

    private function classeMovimentacao(): string
    {
        return $this->tipo === 'venda' ? Venda::class : Compra::class;
    }

    private function ajustarEstoque(Venda|Compra $movimentacao, bool $restaurando): void
    {
        $ehVenda = $this->tipo === 'venda';
        $produtos = $movimentacao->produtos()
            ->withTrashed()
            ->lockForUpdate()
            ->get();

        foreach ($produtos as $produto) {
            $aumentaEstoque = $ehVenda !== $restaurando;
            $quantidade = (int) $produto->pivot->quantidade;

            if (! $aumentaEstoque && $produto->estoque < $quantidade) {
                throw ValidationException::withMessages([
                    'movimentacao' => __('ui.insufficient_stock_restore'),
                ]);
            }
        }

        foreach ($produtos as $produto) {
            $aumentaEstoque = $ehVenda !== $restaurando;
            $quantidade = (int) $produto->pivot->quantidade;

            if ($aumentaEstoque) {
                $produto->increment('estoque', $quantidade);
            } else {
                $produto->decrement('estoque', $quantidade);
            }
        }
    }

    public function render()
    {
        $consulta = $this->classeMovimentacao()::with([
            'produtos' => fn ($query) => $query->withTrashed(),
        ]);

        if ($this->mostrarLixeira) {
            $consulta->onlyTrashed();
        }

        $registros = $consulta
            ->when(
                $this->busca !== '',
                fn ($query) => $query->whereHas(
                    'produtos',
                    fn ($produto) => $produto->withTrashed()->where('name', 'like', '%'.$this->busca.'%')
                )
            )
            ->orderByDesc('data')
            ->paginate($this->porPagina);

        return view('livewire.movimentacao-index', [
            'registros' => $registros,
            'titulo' => __($this->tipo === 'venda' ? 'navigation.sales' : 'navigation.purchases'),
            'totalLixeira' => $this->classeMovimentacao()::onlyTrashed()->count(),
        ]);
    }
}
