@if ($paginator->hasPages())
    <ul class="pagination">
        @if ($paginator->onFirstPage())
            <li class="disabled"><span aria-hidden="true">&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">&laquo;</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span aria-current="page">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}" aria-label="Halaman {{ $page }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">&raquo;</a></li>
        @else
            <li class="disabled"><span aria-hidden="true">&raquo;</span></li>
        @endif
    </ul>
@endif
