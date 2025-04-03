@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        <ul class="pagination">
            {{-- Lien "Précédent" --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link arrow disabled">« Précédent</span></li>
            @else
                <li class="page-item"><a class="page-link arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev">« Précédent</a></li>
            @endif

            {{-- Liens de numéro de page --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Lien "Suivant" --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link arrow" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant »</a></li>
            @else
                <li class="page-item disabled"><span class="page-link arrow disabled">Suivant »</span></li>
            @endif
        </ul>
    </nav>
@endif