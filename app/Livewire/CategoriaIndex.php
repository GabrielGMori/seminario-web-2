<?php

namespace App\Livewire;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriaIndex extends Component
{
    use WithPagination;

    public ?int $categoriaId = null;

    public string $nome = '';

    public string $descricao = '';

    public string $busca = '';

    public int $porPagina = 10;

    public bool $mostrarLixeira = false;

    public function updated(string $property): void
    {
        if (in_array($property, ['busca', 'porPagina'], true)) {
            if ($property === 'porPagina') {
                $this->porPagina = in_array($this->porPagina, [10, 25, 50], true) ? $this->porPagina : 10;
            }

            $this->resetPage();
        }
    }

    public function editar(int $id): void
    {
        $categoria = Categoria::findOrFail($id);
        $this->categoriaId = $categoria->id;
        $this->nome = $categoria->nome;
        $this->descricao = $categoria->descricao ?? '';
    }

    public function cancelar(): void
    {
        $this->reset(['categoriaId', 'nome', 'descricao']);
        $this->resetValidation();
    }

    public function salvar(): void
    {
        $request = new CategoriaRequest;
        abort_unless($request->authorize(), 403);
        $dados = $this->validate($request->rulesFor($this->categoriaId));

        if ($this->categoriaId) {
            $categoria = Categoria::findOrFail($this->categoriaId);
            $categoria->update($dados);
        } else {
            Categoria::create($dados);
        }
        $this->cancelar();
        session()->flash('status', __('ui.category_saved'));
    }

    public function excluir(int $id): void
    {
        $this->resetErrorBag('geral');
        $categoria = Categoria::findOrFail($id);

        if ($categoria->produtos()->withTrashed()->exists()) {
            $this->addError('geral', __('ui.category_delete_blocked'));

            return;
        }
        $categoria->delete();
        session()->flash('status', __('ui.category_deleted'));
    }

    public function alternarLixeira(): void
    {
        $this->mostrarLixeira = ! $this->mostrarLixeira;
        $this->resetPage();
    }

    public function restaurar(int $id): void
    {
        Categoria::onlyTrashed()->findOrFail($id)->restore();

        session()->flash('status', __('ui.category_restored'));
    }

    public function excluirPermanentemente(int $id): void
    {
        $this->resetErrorBag('geral');
        $categoria = Categoria::onlyTrashed()->findOrFail($id);

        if ($categoria->produtos()->withTrashed()->exists()) {
            $this->addError('geral', __('ui.category_permanent_delete_blocked'));

            return;
        }

        $categoria->forceDelete();
        session()->flash('status', __('ui.category_permanently_deleted'));
    }

    public function render()
    {
        $consulta = Categoria::withCount('produtos')
            ->when($this->busca !== '', fn ($q) => $q->where('nome', 'like', '%'.$this->busca.'%'));

        if ($this->mostrarLixeira) {
            $consulta->onlyTrashed();
        }

        return view('livewire.categoria-index', [
            'categorias' => $consulta->orderBy('nome')->paginate($this->porPagina),
            'totalLixeira' => Categoria::onlyTrashed()->count(),
        ]);
    }
}
