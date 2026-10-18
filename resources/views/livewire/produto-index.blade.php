<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Produtos</h1>
            <p class="text-muted mb-0">Consulte e gerencie os produtos cadastrados.</p>
        </div>
        <a href="{{ route('produtos.create') }}" class="btn btn-primary">+ Novo produto</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>
                        <label for="filtroNome" class="form-label">Nome</label>
                        <input id="filtroNome" type="search" class="form-control form-control-sm"
                            placeholder="Filtrar nome..."
                            wire:model.live.debounce.300ms="filtroNome">
                    </th>
                    <th>
                        <label for="filtroIdentificador" class="form-label">Identificador</label>
                        <input id="filtroIdentificador" type="search" class="form-control form-control-sm"
                            placeholder="Filtrar identificador..."
                            wire:model.live.debounce.300ms="filtroIdentificador">
                    </th>
                    <th>
                        <label for="filtroEstoque" class="form-label">Estoque</label>
                        <input id="filtroEstoque" type="number" min="0" step="1" class="form-control form-control-sm"
                            placeholder="Quantidade"
                            wire:model.live.debounce.300ms="filtroEstoque">
                    </th>
                    <th>
                        <label for="filtroCategoria" class="form-label">Categoria</label>
                        <input id="filtroCategoria" type="search" class="form-control form-control-sm"
                            placeholder="Filtrar categoria..."
                            wire:model.live.debounce.300ms="filtroCategoria">
                    </th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produtos as $produto)
                    <tr wire:key="produto-{{ $produto->id }}">
                        <td class="fw-semibold">{{ $produto->name }}</td>
                        <td>{{ $produto->identificador }}</td>
                        <td>{{ $produto->estoque }}</td>
                        <td><span class="badge text-bg-light">{{ $produto->categoria }}</span></td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary"
                                href="{{ route('produtos.edit', $produto->id) }}">Editar</a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                wire:click="excluir({{ $produto->id }})"
                                wire:confirm="Tem certeza de que deseja excluir este produto?"
                                wire:loading.attr="disabled">
                                Excluir
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $produtos->links() }}
    </div>
</div>
