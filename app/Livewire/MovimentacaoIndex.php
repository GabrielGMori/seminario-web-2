<?php
namespace App\Livewire;

use App\Models\Compra;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class MovimentacaoIndex extends Component
{
    public string $tipo = 'venda';
    public string $produto_id = '';
    public string $quantidade = '1';
    public string $valor_unitario = '';
    public string $data_movimentacao = '';
    public string $observacao = '';
    public string $busca = '';
    public int $porPagina = 10;

    public function mount(string $tipo): void
    {
        abort_unless(in_array($tipo, ['venda', 'compra'], true), 404);
        $this->tipo = $tipo;
        $this->data_movimentacao = now()->toDateString();
    }

    public function salvar(): void
    {
        $dados = $this->validate([
            'produto_id' => ['required', 'exists:produtos,id'],
            'quantidade' => ['required', 'integer', 'min:1'],
            'valor_unitario' => ['required', 'numeric', 'min:0.01'],
            'data_movimentacao' => ['required', 'date'],
            'observacao' => ['nullable', 'string', 'max:255'],
        ], [], [
            'produto_id' => 'produto', 'quantidade' => 'quantidade',
            'valor_unitario' => 'valor unitário', 'data_movimentacao' => 'data',
        ]);

        DB::transaction(function () use ($dados) {
            $produto = Produto::query()->lockForUpdate()->findOrFail($dados['produto_id']);
            $quantidade = (int) $dados['quantidade'];

            if ($this->tipo === 'venda' && $produto->estoque < $quantidade) {
                throw ValidationException::withMessages([
                    'quantidade' => 'Estoque insuficiente para realizar esta venda.',
                ]);
            }

            $total = round($quantidade * (float) $dados['valor_unitario'], 2);
            $classe = $this->tipo === 'venda' ? Venda::class : Compra::class;
            $classe::create([
                ...$dados,
                'quantidade' => $quantidade,
                'valor_total' => $total,
                'user_id' => auth()->id(),
            ]);

            $produto->decrement('estoque', $quantidade * ($this->tipo === 'venda' ? 1 : -1));
        });

        $this->reset(['produto_id', 'quantidade', 'valor_unitario', 'observacao']);
        $this->quantidade = '1';
        session()->flash('status', ucfirst($this->tipo) . ' registrada com sucesso!');
    }

    public function render()
    {
        $classe = $this->tipo === 'venda' ? Venda::class : Compra::class;
        $registros = $classe::with('produto')
            ->when($this->busca !== '', fn ($q) => $q->whereHas('produto', fn ($p) => $p->where('name', 'like', '%' . $this->busca . '%')))
            ->latest('data_movimentacao')->latest('id')->paginate($this->porPagina, ['*'], 'page', request()->integer('page', 1));

        return view('livewire.movimentacao-index', [
            'registros' => $registros,
            'produtos' => Produto::orderBy('name')->get(),
            'titulo' => $this->tipo === 'venda' ? 'Vendas' : 'Compras',
        ]);
    }
}
