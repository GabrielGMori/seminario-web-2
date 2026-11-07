<div>
    <div class="mb-4">
        <h1 class="h2 mb-1">Categorias</h1>
        <p class="text-muted mb-0">Organize os produtos por categoria.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @error('geral')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror

    <form wire:submit="salvar" class="card card-body shadow-sm mb-4">
        <h2 class="h5">
            {{ $categoriaId ? 'Editar categoria' : 'Nova categoria' }}
        </h2>

        <div class="row g-3">
            <div class="col-md-5">
                <label for="nome" class="form-label">Nome</label>

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
                <label for="descricao" class="form-label">Descrição</label>

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
                    Salvar
                </button>

                @if ($categoriaId)
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        wire:click="cancelar"
                    >
                        Cancelar
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
            placeholder="Buscar categoria..."
            wire:model.live.debounce.300ms="busca"
        >

        <select
            class="form-select form-select-sm"
            style="width:auto"
            wire:model.live="porPagina"
        >
            <option value="10">10 por página</option>
            <option value="25">25 por página</option>
            <option value="50">50 por página</option>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Produtos vinculados</th>
                    <th class="text-end">Ações</th>
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

                        <td class="text-end text-nowrap">
                            <button
                                class="btn btn-sm btn-outline-primary"
                                wire:click="editar({{ $categoria->id }})"
                            >
                                Editar
                            </button>

                            <button
                                class="btn btn-sm btn-outline-danger"
                                wire:click="excluir({{ $categoria->id }})"
                                wire:confirm="Excluir esta categoria?"
                            >
                                Excluir
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Nenhuma categoria cadastrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Total: {{ $categorias->total() }} categorias
        </small>

        {{ $categorias->links() }}
    </div>
</div>