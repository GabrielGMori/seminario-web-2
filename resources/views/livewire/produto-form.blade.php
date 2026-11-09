<div class="mx-auto" style="max-width: 760px">
    <a href="{{ route('produtos.index') }}" class="text-decoration-none">&larr; @lang('ui.back_to_products')</a>

    <div class="mt-3 mb-4">
        <h1 class="h2 mb-1">{{ $editando ? __('ui.edit_product') : __('ui.new_product') }}</h1>
        <p class="text-muted mb-0">
            {{ $editando ? __('ui.product_updated_help') : __('ui.product_create_help') }}
        </p>
    </div>

    <form wire:submit="salvar" class="card card-body">
        <div class="mb-3">
            <label for="name" class="form-label">@lang('ui.product_name')</label>
            <input id="name" type="text" maxlength="255"
                class="form-control @error('name') is-invalid @enderror"
                wire:model="name" autocomplete="off">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label for="estoque" class="form-label">@lang('ui.stock_quantity')</label>
                <input id="estoque" type="number" min="0"
                    class="form-control @error('estoque') is-invalid @enderror" wire:model="estoque">
                @error('estoque') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label for="preco_compra" class="form-label">@lang('ui.purchase_price') (R$)</label>
                <input id="preco_compra" type="number" min="0" step="0.01"
                    class="form-control @error('preco_compra') is-invalid @enderror" wire:model="preco_compra">
                @error('preco_compra') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label for="preco_venda" class="form-label">@lang('ui.sale_price') (R$)</label>
                <input id="preco_venda" type="number" min="0" step="0.01"
                    class="form-control @error('preco_venda') is-invalid @enderror" wire:model="preco_venda">
                @error('preco_venda') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="categoria_id" class="form-label">@lang('ui.category')</label>
                <select id="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror"
                    wire:model="categoria_id">
                    <option value="">@lang('ui.select_category')</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @endforeach
                </select>
                @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if ($categorias->isEmpty())
                    <div class="form-text">@lang('ui.create_category_first')</div>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">@lang('ui.cancel')</a>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $editando ? __('ui.save_changes') : __('ui.create_product') }}</span>
                <span wire:loading>@lang('ui.saving')</span>
            </button>
        </div>
    </form>
</div>
