<div>
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="h2 mb-1">{{ $titulo }}</h1>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary" wire:click="alternarLixeira">
                    {{ $mostrarLixeira ? __('ui.back_to_list') : __('ui.trash') }}
                    @unless ($mostrarLixeira)
                        <span class="badge text-bg-secondary">{{ $totalLixeira }}</span>
                    @endunless
                </button>
                @unless ($mostrarLixeira)
                    <a href="{{ route($tipo === 'venda' ? 'vendas.create' : 'compras.create') }}" class="btn btn-primary">
                        + {{ __('ui.register_movement', ['type' => strtolower(rtrim($titulo, 's'))]) }}
                    </a>
                @endunless
            </div>
        </div>
    </div>

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    @error('movimentacao')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror

    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
        <input type="search" class="form-control" style="max-width: 320px" placeholder="@lang('ui.search_product')"
            wire:model.live.debounce.300ms="busca">
    </div>

    @if ($mostrarLixeira)
        <h2 class="h5 mb-3">@lang('ui.movements_trash')</h2>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>@lang('ui.date')</th>
                    <th>@lang('ui.products')</th>
                    <th>@lang('ui.total')</th>
                    @if ($mostrarLixeira)
                        <th>@lang('ui.deleted_at')</th>
                    @endif
                    <th class="text-end">@lang('ui.actions')</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($registros as $registro)
                <tr wire:key="mov-{{ $tipo }}-{{ $registro->id }}">
                    <td>
                        {{ $registro->data->format('d/m/Y') }}
                    </td>

                    <td>
                        @forelse ($registro->produtos as $produto)
                        <div>{{ $produto->name }} ({{ $produto->pivot->quantidade }} @lang('ui.unit_abbreviation'))</div>
                        @empty
                        <span class="text-muted">—</span>
                        @endforelse
                    </td>

                    <td class="fw-semibold">
                        R$ {{ number_format($registro->preco ?? 0, 2, ',', '.') }}
                    </td>
                    @if ($mostrarLixeira)
                        <td>{{ $registro->deleted_at?->format('d/m/Y H:i') }}</td>
                    @endif
                    <td class="text-end text-nowrap">
                        @if ($mostrarLixeira)
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                wire:click="restaurar({{ $registro->id }})">@lang('ui.restore')</button>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                wire:click="excluirPermanentemente({{ $registro->id }})"
                                wire:confirm="@lang('ui.confirm_permanent_movement')"
                                wire:loading.attr="disabled">@lang('ui.delete_permanently')</button>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                wire:click="excluir({{ $registro->id }})"
                                wire:confirm="@lang('ui.confirm_trash_movement')"
                                wire:loading.attr="disabled">@lang('ui.delete')</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $mostrarLixeira ? 5 : 4 }}" class="text-center text-muted py-4">
                        {{ $mostrarLixeira ? __('ui.empty_trash') : __('ui.no_movements') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$registros" :label="__('ui.records')" />
</div>
