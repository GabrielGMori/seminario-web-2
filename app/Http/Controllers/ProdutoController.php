<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Produto;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('produtos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdutoRequest $request)
    {
        Produto::create($request->validated());

        return redirect()->route('produtos.index')->with('status', __('ui.product_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $produto)
    {
        Produto::findOrFail($produto);

        return view('produtos.form');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdutoRequest $request, int $produto)
    {
        $produto = Produto::findOrFail($produto);
        $produto->update($request->validated());

        return redirect()->route('produtos.index')->with('status', __('ui.product_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $produto)
    {
        $produto = Produto::findOrFail($produto);
        $produto->delete();

        return redirect()->route('produtos.index')->with('status', __('ui.product_deleted'));
    }
}
