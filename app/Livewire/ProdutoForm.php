<?php

namespace App\Livewire;

use App\Models\Produto;
use Livewire\Component;

class ProdutoForm extends Component
{
    public ?int $produtoId = null;
    public string $name = '';
    public string $identificador = '';
    public string $estoque = '';
    public string $categoria = '';

    public function mount(?int $produto = null): void
    {
        if ($produto !== null) {
            $registro = Produto::findOrFail($produto);

            $this->produtoId = $registro->id;
            $this->name = $registro->name;
            $this->identificador = $registro->identificador;
            $this->estoque = (string) $registro->estoque;
            $this->categoria = $registro->categoria;
        }
    }

    public function salvar()
    {
        $dados = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'identificador' => ['required', 'string', 'max:255'],
            'estoque' => ['required', 'integer', 'min:0'],
            'categoria' => ['required', 'string', 'max:255'],
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser um texto.',
            'max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'min' => 'O campo :attribute não pode ser menor que :min.',
        ], [
            'name' => 'nome do produto',
            'identificador' => 'identificador',
            'estoque' => 'quantidade em estoque',
            'categoria' => 'categoria',
        ]);

        if ($this->produtoId) {
            Produto::findOrFail($this->produtoId)->update($dados);
            session()->flash('status', 'Produto atualizado com sucesso!');
        } else {
            Produto::create($dados);
            session()->flash('status', 'Produto cadastrado com sucesso!');
        }

        return $this->redirectRoute('produtos.index');
    }

    public function render()
    {
        return view('livewire.produto-form', [
            'editando' => $this->produtoId !== null,
        ]);
    }
}
