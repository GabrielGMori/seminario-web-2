@props(['paginator', 'label' => null])

@php($pageName = $paginator->getPageName())
@php($elements = \Illuminate\Pagination\UrlWindow::make($paginator))

<div class="d-flex justify-content-between align-items-center gap-2">
    <small class="text-muted">
        {{ __('ui.showing', [
            'first' => $paginator->firstItem() ?? 0,
            'last' => $paginator->lastItem() ?? 0,
            'total' => $paginator->total(),
            'label' => $label ?? __('ui.records'),
        ]) }}
    </small>

    <div class="d-flex align-items-center gap-2">
        <label for="{{ $pageName }}PorPagina" class="small text-muted">@lang('ui.show')</label>
        <select id="{{ $pageName }}PorPagina" class="form-select form-select-sm" style="width: auto"
            wire:model.live="porPagina">
            <option value="10" @selected($paginator->perPage() === 10)>10</option>
            <option value="25" @selected($paginator->perPage() === 25)>25</option>
            <option value="50" @selected($paginator->perPage() === 50)>50</option>
        </select>
        <span class="small text-muted">@lang('ui.per_page')</span>
    </div>

    @if ($paginator->hasPages())
    <nav aria-label="@lang('ui.pagination')">
        <ul class="pagination mb-0">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())
                <span class="page-link" aria-disabled="true">@lang('pagination.previous')</span>
                @else
                <button type="button" class="page-link" wire:click="previousPage('{{ $pageName }}')"
                    wire:loading.attr="disabled" aria-label="@lang('pagination.previous')">
                    @lang('pagination.previous')
                </button>
                @endif
            </li>

            @foreach ($elements as $element)
            @if (is_string($element))
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">{{ $element }}</span>
            </li>
            @elseif (is_array($element))
            @foreach ($element as $page => $url)
            <li class="page-item {{ $page === $paginator->currentPage() ? 'active' : '' }}"
                wire:key="pagination-{{ $pageName }}-{{ $page }}" @if ($page===$paginator->currentPage())
                aria-current="page" @endif>
                @if ($page === $paginator->currentPage())
                <span class="page-link">{{ $page }}</span>
                @else
                <button type="button" class="page-link" wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                    wire:loading.attr="disabled">
                    {{ $page }}
                </button>
                @endif
            </li>
            @endforeach
            @endif
            @endforeach

            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())
                <button type="button" class="page-link" wire:click="nextPage('{{ $pageName }}')"
                    wire:loading.attr="disabled" aria-label="@lang('pagination.next')">
                    @lang('pagination.next')
                </button>
                @else
                <span class="page-link" aria-disabled="true">@lang('pagination.next')</span>
                @endif
            </li>
        </ul>
    </nav>
    @else
        <div class="text-muted small">@lang('ui.no_more_pages')</div>
    @endif
</div>
