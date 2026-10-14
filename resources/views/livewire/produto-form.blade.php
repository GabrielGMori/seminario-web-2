<div class="mx-auto" style="max-width: 760px;">
    <a href="{{ route('produtos.index') }}" class="text-decoration-none">&larr; Voltar para produtos</a>

    <div class="mt-3 mb-4">
        <h1 class="h2 mb-1">{{ $editando ? 'Editar produto' : 'Cadastrar produto' }}</h1>
        <p class="text-muted mb-0">
            {{ $editando ? 'Atualize os dados do produto.' : 'Preencha os dados para adicionar um produto ao estoque.' }}
        </p>
    </div>

    <form wire:submit="salvar" class="card card-body shadow-sm">
        <div class="mb-3">
            <label for="name" class="form-label">Nome do produto</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                wire:model="name" autocomplete="off">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="identificador" class="form-label">Identificador</label>
            <input id="identificador" type="text"
                class="form-control @error('identificador') is-invalid @enderror"
                wire:model="identificador" autocomplete="off">
            @error('identificador') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="estoque" class="form-label">Quantidade em estoque</label>
                <input id="estoque" type="number" min="0"
                    class="form-control @error('estoque') is-invalid @enderror"
                    wire:model="estoque">
                @error('estoque') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <input id="categoria" type="text"
                    class="form-control @error('categoria') is-invalid @enderror"
                    wire:model="categoria" autocomplete="off">
                @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-2">
            <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $editando ? 'Salvar alterações' : 'Cadastrar produto' }}</span>
                <span wire:loading>Salvando...</span>
            </button>
        </div>
    </form>
</div>
