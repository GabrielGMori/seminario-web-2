<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">@lang('ui.report_title')</h1>
            <p class="text-muted mb-0">@lang('ui.report_intro')</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <label class="small" for="inicio">@lang('ui.from')</label>
            <input
                id="inicio"
                type="date"
                class="form-control form-control-sm"
                wire:model.live="inicio"
            >
            @error('inicio') <span class="text-danger small">{{ $message }}</span> @enderror

            <label class="small" for="fim">@lang('ui.until')</label>
            <input
                id="fim"
                type="date"
                class="form-control form-control-sm"
                wire:model.live="fim"
            >
            @error('fim') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">@lang('ui.sales_total')</div>
                <div class="h3 mb-0">
                    R$ {{ number_format($totalVendas, 2, ',', '.') }}
                </div>
                <small class="text-muted">
                    {{ trans_choice('ui.sales_units', $quantidadeVendas, ['count' => $quantidadeVendas]) }}
                </small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">@lang('ui.purchases_total')</div>
                <div class="h3 mb-0">
                    R$ {{ number_format($totalCompras, 2, ',', '.') }}
                </div>
                <small class="text-muted">
                    {{ trans_choice('ui.purchases_units', $quantidadeCompras, ['count' => $quantidadeCompras]) }}
                </small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">@lang('ui.movement_balance')</div>
                <div class="h3 mb-0">
                    R$ {{ number_format($totalVendas - $totalCompras, 2, ',', '.') }}
                </div>
                <small class="text-muted">@lang('ui.sales_minus_purchases')</small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body h-100">
                <div class="text-muted small">@lang('ui.units_in_stock')</div>
                <div class="h3 mb-0">{{ $estoqueTotal }}</div>
                <small class="text-muted">@lang('ui.current_stock')</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card card-body h-100">
                <h2 class="h5">@lang('ui.low_stock_products')</h2>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>@lang('ui.product')</th>
                                <th>@lang('ui.category')</th>
                                <th>@lang('ui.stock')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($produtosBaixos as $produto)
                                <tr>
                                    <td>{{ $produto->name }}</td>
                                    <td>
                                        {{ $produto->categoria?->nome ?? '—' }}
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
                                        @lang('ui.low_stock_empty')
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
                <h2 class="h5">@lang('ui.recent_sales')</h2>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>@lang('ui.date')</th>
                                <th>@lang('ui.product')</th>
                                <th>@lang('ui.total')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($vendasRecentes as $venda)
                                <tr>
                                    <td>
                                        {{ $venda->data->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @foreach ($venda->produtos as $produto)
                                            <div>{{ $produto->name }} ({{ $produto->pivot->quantidade }} @lang('ui.unit_abbreviation'))</div>
                                        @endforeach
                                    </td>
                                    <td>
                                        R$ {{ number_format($venda->preco, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">
                                        @lang('ui.no_sales_period')
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
                <h2 class="h5">@lang('ui.recent_purchases')</h2>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>@lang('ui.date')</th>
                                <th>@lang('ui.product')</th>
                                <th>@lang('ui.total')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($comprasRecentes as $compra)
                                <tr>
                                    <td>
                                        {{ $compra->data->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @foreach ($compra->produtos as $produto)
                                            <div>{{ $produto->name }} ({{ $produto->pivot->quantidade }} @lang('ui.unit_abbreviation'))</div>
                                        @endforeach
                                    </td>
                                    <td>
                                        R$ {{ number_format($compra->preco, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">
                                        @lang('ui.no_purchases_period')
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