<div class="mx-auto" style="max-width: 900px;">
    <a href="{{ route($tipo === 'venda' ? 'vendas.index' : 'compras.index') }}" class="text-decoration-none">
        &larr; {{ __('ui.back_to_list') }}
    </a>

    <div class="mt-3 mb-4">
        <h1 class="h2 mb-1">
            {{ __('ui.register_movement', ['type' => __($tipo === 'venda' ? 'ui.sale_noun' : 'ui.purchase_noun')]) }}
        </h1>
        <p class="text-muted mb-0">@lang('ui.movement_form_help')</p>
    </div>

    <form wire:submit="salvar" class="card card-body shadow-sm">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label" for="data">@lang('ui.date')</label>
                <input id="data" type="date"
                    class="form-control @error('data') is-invalid @enderror"
                    wire:model="data">
                @error('data')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-8">
                <label class="form-label" for="precoTotal">@lang('ui.total_price') (R$)</label>
                <input id="precoTotal" type="number" min="0" step="0.01"
                    class="form-control @error('precoTotal') is-invalid @enderror"
                    wire:model.live="precoTotal">
                @error('precoTotal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    {{ __('ui.expected_price', ['price' => number_format((float) $precoEsperado, 2, ',', '.')]) }}
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">@lang('ui.products')</h2>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="adicionarItem">
                + @lang('ui.add_product')
            </button>
        </div>

        @foreach ($produtos as $index => $item)
            <div class="row g-3 align-items-end border rounded p-3 mb-3" wire:key="item-{{ $index }}">
                <div class="col-md-6">
                    <label class="form-label" for="produto_{{ $index }}">@lang('ui.product')</label>
                    <select id="produto_{{ $index }}"
                        class="form-select @error('produtos.'.$index.'.produto_id') is-invalid @enderror"
                        wire:model.live="produtos.{{ $index }}.produto_id">
                        <option value="">@lang('ui.select_product')</option>
                        @foreach ($produtosDisponiveis as $produto)
                            <option value="{{ $produto->id }}">
                                {{ $produto->name }} — R$ {{ number_format((float) $produto->{$colunaPreco}, 2, ',', '.') }}
                                ({{ __('ui.stock_label', ['count' => $produto->estoque]) }})
                            </option>
                        @endforeach
                    </select>
                    @error('produtos.'.$index.'.produto_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="quantidade_{{ $index }}">@lang('ui.quantity')</label>
                    <input id="quantidade_{{ $index }}" type="number" min="1"
                        max="{{ $tipo === 'venda' ? $produtosDisponiveis->firstWhere('id', (int) $item['produto_id'])?->estoque : null }}"
                        class="form-control @error('produtos.'.$index.'.quantidade') is-invalid @enderror"
                        wire:model.live.debounce.300ms="produtos.{{ $index }}.quantidade">
                    @error('produtos.'.$index.'.quantidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($selectedProduct = $produtosDisponiveis->firstWhere('id', (int) $item['produto_id']))
                        <div class="form-text">
                            {{ __('ui.expected', ['price' => number_format((float) $selectedProduct->{$colunaPreco} * (int) $item['quantidade'], 2, ',', '.')]) }}
                        </div>
                    @endif
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-danger w-100"
                        wire:click="removerItem({{ $index }})">
                        @lang('ui.remove')
                    </button>
                </div>
            </div>
        @endforeach

        @if ($produtosDisponiveis->isEmpty())
            <p class="text-muted">@lang('ui.create_products_before_movement')</p>
        @endif

        <div class="d-flex justify-content-end gap-2 mt-2">
            <a href="{{ route($tipo === 'venda' ? 'vendas.index' : 'compras.index') }}"
                class="btn btn-outline-secondary">@lang('ui.cancel')</a>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                @disabled($produtosDisponiveis->isEmpty())>
                {{ __('ui.register_movement_button', ['type' => __($tipo === 'venda' ? 'ui.sale_noun' : 'ui.purchase_noun')]) }}
            </button>
        </div>
    </form>
</div>
