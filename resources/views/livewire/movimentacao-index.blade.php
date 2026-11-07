<div>
    <div class="mb-4">
        <h1 class="h2 mb-1">{{ $titulo }}</h1>
        <p class="text-muted mb-0">
            Registre e consulte as movimentações de {{ strtolower($titulo) }}.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="salvar" class="card card-body shadow-sm mb-4">
        <h2 class="h5 mb-3">
            Registrar {{ strtolower(rtrim($titulo, 's')) }}
        </h2>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="produto_id">Produto</label>

                <select
                    id="produto_id"
                    class="form-select @error('produto_id') is-invalid @enderror"
                    wire:model="produto_id"
                >
                    <option value="">Selecione um produto</option>

                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}">
                            {{ $produto->name }} (estoque: {{ $produto->estoque }})
                        </option>
                    @endforeach
                </select>

                @error('produto_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label" for="quantidade">Quantidade</label>

                <input
                    id="quantidade"
                    type="number"
                    min="1"
                    class="form-control @error('quantidade') is-invalid @enderror"
                    wire:model="quantidade"
                >

                @error('quantidade')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label" for="valor_unitario">Valor unitário (R$)</label>

                <input
                    id="valor_unitario"
                    type="number"
                    min="0.01"
                    step="0.01"
                    class="form-control @error('valor_unitario') is-invalid @enderror"
                    wire:model="valor_unitario"
                >

                @error('valor_unitario')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label" for="data_movimentacao">Data</label>

                <input
                    id="data_movimentacao"
                    type="date"
                    class="form-control @error('data_movimentacao') is-invalid @enderror"
                    wire:model="data_movimentacao"
                >

                @error('data_movimentacao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button
                    class="btn btn-primary w-100"
                    type="submit"
                    wire:loading.attr="disabled"
                >
                    Registrar
                </button>
            </div>

            <div class="col-12">
                <label class="form-label" for="observacao">
                    Observação (opcional)
                </label>

                <input
                    id="observacao"
                    class="form-control"
                    wire:model="observacao"
                    maxlength="255"
                >

                @error('observacao')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </form>

    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
        <input
            type="search"
            class="form-control"
            style="max-width: 320px"
            placeholder="Buscar pelo produto..."
            wire:model.live.debounce.300ms="busca"
        >

        <div class="d-flex align-items-center gap-2">
            <label for="porPagina" class="small text-muted">Exibir</label>

            <select
                id="porPagina"
                class="form-select form-select-sm"
                style="width: auto"
                wire:model.live="porPagina"
            >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

            <span class="small text-muted">por página</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor unitário</th>
                    <th>Total</th>
                    <th>Observação</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($registros as $registro)
                    <tr wire:key="mov-{{ $tipo }}-{{ $registro->id }}">
                        <td>
                            {{ $registro->data_movimentacao->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ $registro->produto->name }}
                        </td>

                        <td>
                            {{ $registro->quantidade }}
                        </td>

                        <td>
                            R$ {{ number_format($registro->valor_unitario, 2, ',', '.') }}
                        </td>

                        <td class="fw-semibold">
                            R$ {{ number_format($registro->valor_total, 2, ',', '.') }}
                        </td>

                        <td>
                            {{ $registro->observacao ?: '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Nenhuma movimentação encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <small class="text-muted">
            Mostrando {{ $registros->firstItem() ?? 0 }}–{{ $registros->lastItem() ?? 0 }}
            de {{ $registros->total() }} registros
        </small>

        {{ $registros->links() }}
    </div>
</div>