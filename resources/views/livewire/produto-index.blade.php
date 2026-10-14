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

    <div class="mb-3">
        <label for="busca" class="form-label">Buscar produtos</label>
        <input id="busca" type="search" class="form-control"
            placeholder="Nome, identificador ou categoria..."
            wire:model.live.debounce.300ms="busca">
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Identificador</th>
                    <th>Estoque</th>
                    <th>Categoria</th>
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
