<?php
namespace App\Livewire;
use App\Models\Categoria;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CategoriaIndex extends Component
{
    public ?int $categoriaId = null;
    public string $nome = '';
    public string $descricao = '';
    public string $busca = '';
    public int $porPagina = 10;

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
        $dados = $this->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('categorias', 'nome')->ignore($this->categoriaId)],
            'descricao' => ['nullable', 'string', 'max:1000'],
        ], [], ['nome' => 'nome', 'descricao' => 'descrição']);

        if ($this->categoriaId) {
            $categoria = Categoria::findOrFail($this->categoriaId);
            $categoria->update($dados);
            $categoria->produtos()->update(['categoria' => $dados['nome']]);
        } else {
            Categoria::create($dados);
        }
        $this->cancelar();
        session()->flash('status', 'Categoria salva com sucesso!');
    }

    public function excluir(int $id): void
    {
        $categoria = Categoria::findOrFail($id);
        if ($categoria->produtos()->exists()) {
            $this->addError('geral', 'Não é possível excluir uma categoria que possui produtos vinculados.');
            return;
        }
        $categoria->delete();
        session()->flash('status', 'Categoria excluída com sucesso!');
    }

    public function render()
    {
        return view('livewire.categoria-index', [
            'categorias' => Categoria::withCount('produtos')
                ->when($this->busca !== '', fn ($q) => $q->where('nome', 'like', '%' . $this->busca . '%'))
                ->orderBy('nome')->paginate($this->porPagina, ['*'], 'page', request()->integer('page', 1)),
        ]);
    }
}
