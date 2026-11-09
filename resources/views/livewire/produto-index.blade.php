<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">@lang('navigation.products')</h1>
            <p class="text-muted mb-0">@lang('ui.product_list_intro')</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" wire:click="alternarLixeira">
                {{ $mostrarLixeira ? __('ui.back_to_products_list') : __('ui.trash') }}
                @unless ($mostrarLixeira)
                    <span class="badge text-bg-secondary">{{ $totalLixeira }}</span>
                @endunless
            </button>
            @unless ($mostrarLixeira)
                <a href="{{ route('produtos.create') }}" class="btn btn-primary">
                    + @lang('ui.new_product')
                </a>
            @endunless
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @error('geral')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror

    @if ($mostrarLixeira)
        <h2 class="h5 mb-3">@lang('ui.products_trash')</h2>
    @else
        <div class="card card-body mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-lg-2">
                    <label for="filtroNome" class="form-label small mb-1">@lang('ui.name')</label>
                    <input id="filtroNome" type="search" class="form-control form-control-sm"
                        placeholder="@lang('ui.product')" wire:model.live.debounce.300ms="filtroNome">
                </div>
                <div class="col-6 col-lg-1">
                    <label for="filtroEstoque" class="form-label small mb-1">@lang('ui.stock')</label>
                    <input id="filtroEstoque" type="number" min="0" class="form-control form-control-sm"
                        wire:model.live.debounce.300ms="filtroEstoque">
                </div>
                <fieldset class="col-6 col-lg-3">
                    <legend class="form-label small mb-1">@lang('ui.purchase_price') (R$)</legend>
                    <div class="d-flex gap-1">
                        <input type="number" min="0" step="0.01" class="form-control form-control-sm"
                            aria-label="@lang('ui.purchase_price_min')" placeholder="@lang('ui.minimum')"
                            wire:model.live.debounce.300ms="precoCompraMinimo">
                        <input type="number" min="0" step="0.01" class="form-control form-control-sm"
                            aria-label="@lang('ui.purchase_price_max')" placeholder="@lang('ui.maximum')"
                            wire:model.live.debounce.300ms="precoCompraMaximo">
                    </div>
                </fieldset>
                <fieldset class="col-6 col-lg-3">
                    <legend class="form-label small mb-1">@lang('ui.sale_price') (R$)</legend>
                    <div class="d-flex gap-1">
                        <input type="number" min="0" step="0.01" class="form-control form-control-sm"
                            aria-label="@lang('ui.sale_price_min')" placeholder="@lang('ui.minimum')"
                            wire:model.live.debounce.300ms="precoVendaMinimo">
                        <input type="number" min="0" step="0.01" class="form-control form-control-sm"
                            aria-label="@lang('ui.sale_price_max')" placeholder="@lang('ui.maximum')"
                            wire:model.live.debounce.300ms="precoVendaMaximo">
                    </div>
                </fieldset>
                <div class="col-8 col-lg-2">
                    <label for="filtroCategoria" class="form-label small mb-1">@lang('ui.category')</label>
                    <input id="filtroCategoria" type="search" class="form-control form-control-sm"
                        placeholder="@lang('ui.category')" wire:model.live.debounce.300ms="filtroCategoria">
                </div>
                <div class="col-4 col-lg-1">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                        wire:click="limparFiltros">@lang('ui.clear')</button>
                </div>
            </div>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>@lang('ui.name')</th>
                    <th>@lang('ui.stock')</th>
                    <th>@lang('ui.purchase')</th>
                    <th>@lang('ui.sale')</th>
                    <th>@lang('ui.category')</th>
                    @if ($mostrarLixeira)
                        <th>@lang('ui.deleted_at')</th>
                    @endif
                    <th class="text-end">@lang('ui.actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produtos as $produto)
                    <tr wire:key="produto-{{ $produto->id }}">
                        <td class="fw-semibold">{{ $produto->name }}</td>
                        <td>{{ $produto->estoque }}</td>
                        <td>R$ {{ number_format((float) $produto->preco_compra, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format((float) $produto->preco_venda, 2, ',', '.') }}</td>
                        <td><span class="badge text-bg-light">{{ $produto->categoria?->nome ?? '—' }}</span></td>
                        @if ($mostrarLixeira)
                            <td>{{ $produto->deleted_at?->format('d/m/Y H:i') }}</td>
                        @endif
                        <td class="text-end text-nowrap">
                            @if ($mostrarLixeira)
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="restaurar({{ $produto->id }})">@lang('ui.restore')</button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    wire:click="excluirPermanentemente({{ $produto->id }})"
                                    wire:confirm="@lang('ui.confirm_permanent_product')"
                                    wire:loading.attr="disabled">@lang('ui.delete_permanently')</button>
                            @else
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('produtos.edit', $produto->id) }}">@lang('ui.edit')</a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    wire:click="excluir({{ $produto->id }})"
                                    wire:confirm="@lang('ui.confirm_trash_product')"
                                    wire:loading.attr="disabled">@lang('ui.delete')</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $mostrarLixeira ? 7 : 6 }}" class="text-center text-muted py-5">
                            {{ $mostrarLixeira ? __('ui.empty_trash') : __('ui.no_products') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <x-pagination :paginator="$produtos" label="{{ __('ui.products') }}" />
    </div>
</div>
