<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Relatórios</h1>
            <p class="text-muted mb-0">Resumo das movimentações e do estoque.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <label class="small" for="inicio">De</label>
            <input
                id="inicio"
                type="date"
                class="form-control form-control-sm"
                wire:model.live="inicio"
            >

            <label class="small" for="fim">Até</label>
            <input
                id="fim"
                type="date"
                class="form-control form-control-sm"
                wire:model.live="fim"
            >
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">Total em vendas</div>
                <div class="h3 mb-0">
                    R$ {{ number_format($totalVendas, 2, ',', '.') }}
                </div>
                <small class="text-muted">
                    {{ $quantidadeVendas }} unidades vendidas
                </small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">Total em compras</div>
                <div class="h3 mb-0">
                    R$ {{ number_format($totalCompras, 2, ',', '.') }}
                </div>
                <small class="text-muted">
                    {{ $quantidadeCompras }} unidades compradas
                </small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">Saldo de movimentações</div>
                <div class="h3 mb-0">
                    R$ {{ number_format($totalVendas - $totalCompras, 2, ',', '.') }}
                </div>
                <small class="text-muted">Vendas menos compras</small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">Unidades em estoque</div>
                <div class="h3 mb-0">{{ $estoqueTotal }}</div>
                <small class="text-muted">Estoque atual</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card card-body h-100">
                <h2 class="h5">Produtos com estoque baixo</h2>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Estoque</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($produtosBaixos as $produto)
                                <tr>
                                    <td>{{ $produto->name }}</td>
                                    <td>
                                        {{ $produto->categoriaRelacionada?->nome ?? $produto->categoria }}
                                    </td>
                                    <td>
                                        <span class="badge text-bg-warning">
                                            {{ $produto->estoque }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">
                                        Nenhum produto com estoque baixo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-body h-100">
                <h2 class="h5">Vendas recentes no período</h2>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Produto</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($vendasRecentes as $venda)
                                <tr>
                                    <td>
                                        {{ $venda->data_movimentacao->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $venda->produto->name }}</td>
                                    <td>
                                        R$ {{ number_format($venda->valor_total, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">
                                        Sem vendas no período.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-body h-100">
                <h2 class="h5">Compras recentes no período</h2>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Produto</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($comprasRecentes as $compra)
                                <tr>
                                    <td>
                                        {{ $compra->data_movimentacao->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $compra->produto->name }}</td>
                                    <td>
                                        R$ {{ number_format($compra->valor_total, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">
                                        Sem compras no período.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>