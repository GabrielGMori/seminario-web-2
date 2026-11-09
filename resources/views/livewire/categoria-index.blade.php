<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">@lang('ui.category_title')</h1>
            <p class="text-muted mb-0">@lang('ui.category_intro')</p>
        </div>
        <button type="button" class="btn btn-outline-secondary" wire:click="alternarLixeira">
            {{ $mostrarLixeira ? __('ui.back_to_categories') : __('ui.trash') }}
            @unless ($mostrarLixeira)
                <span class="badge text-bg-secondary">{{ $totalLixeira }}</span>
            @endunless
        </button>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @error('geral')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror

    @unless ($mostrarLixeira)
    <form wire:submit="salvar" class="card card-body shadow-sm mb-4">
        <h2 class="h5">
            {{ $categoriaId ? __('ui.edit_category') : __('ui.new_category') }}
        </h2>

        <div class="row g-3">
            <div class="col-md-5">
                <label for="nome" class="form-label">@lang('ui.name')</label>

                <input
                    id="nome"
                    class="form-control @error('nome') is-invalid @enderror"
                    wire:model="nome"
                >

                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-5">
                <label for="descricao" class="form-label">@lang('ui.description')</label>

                <input
                    id="descricao"
                    class="form-control @error('descricao') is-invalid @enderror"
                    wire:model="descricao"
                >

                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button class="btn btn-primary" type="submit">
                    @lang('ui.save')
                </button>

                @if ($categoriaId)
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        wire:click="cancelar"
                    >
                        @lang('ui.cancel')
                    </button>
                @endif
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
        <input
            type="search"
            class="form-control"
            style="max-width:320px"
            placeholder="@lang('ui.search_category')"
            wire:model.live.debounce.300ms="busca"
        >

    </div>
    @else
        <h2 class="h5 mb-3">@lang('ui.categories_trash')</h2>
    @endunless

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>@lang('ui.category')</th>
                    <th>@lang('ui.description')</th>
                    <th>@lang('ui.linked_products')</th>
                    @if ($mostrarLixeira)
                        <th>@lang('ui.deleted_at')</th>
                    @endif
                    <th class="text-end">@lang('ui.actions')</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categorias as $categoria)
                    <tr wire:key="categoria-{{ $categoria->id }}">
                        <td class="fw-semibold">
                            {{ $categoria->nome }}
                        </td>

                        <td>
                            {{ $categoria->descricao ?: '—' }}
                        </td>

                        <td>
                            {{ $categoria->produtos_count }}
                        </td>

                        @if ($mostrarLixeira)
                            <td>{{ $categoria->deleted_at?->format('d/m/Y H:i') }}</td>
                        @endif
                        <td class="text-end text-nowrap">
                            @if ($mostrarLixeira)
                                <button class="btn btn-sm btn-outline-primary"
                                    wire:click="restaurar({{ $categoria->id }})">@lang('ui.restore')</button>
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="excluirPermanentemente({{ $categoria->id }})"
                                    wire:confirm="@lang('ui.confirm_permanent_category')"
                                    wire:loading.attr="disabled">@lang('ui.delete_permanently')</button>
                            @else
                                <button class="btn btn-sm btn-outline-primary"
                                    wire:click="editar({{ $categoria->id }})">@lang('ui.edit')</button>
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="excluir({{ $categoria->id }})"
                                    wire:confirm="@lang('ui.confirm_trash_category')">@lang('ui.delete')</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $mostrarLixeira ? 5 : 4 }}" class="text-center text-muted py-4">
                            {{ $mostrarLixeira ? __('ui.empty_trash') : __('ui.no_categories') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$categorias" :label="__('navigation.categories')" />
</div>