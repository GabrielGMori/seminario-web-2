<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = Produto::paginate(10);

        return view('produtos.index', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'identificador' => 'required|string',
            'estoque' => 'required|integer',
            'categoria' => 'required|string',
        ]);

        Produto::create($validated);

        return redirect()->route('produtos.index')->with('status', 'Produto cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produto $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'identificador' => 'sometimes|required|string',
            'estoque' => 'sometimes|required|integer',
            'categoria' => 'sometimes|required|string',
        ]);

        $produto->update($validated);

        return redirect()->route('produtos.index')->with('status', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')->with('status', 'Produto excluído com sucesso!');
    }
}
