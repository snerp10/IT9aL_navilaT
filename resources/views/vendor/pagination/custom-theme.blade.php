@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center" style="margin: 0;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link" style="background:#d0dcad; color:#16726d; border:none;">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="background:#16726d; color:#fff; border:none;">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link" style="background:#fff; color:#16726d; border:none;">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link" style="background:#16726d; color:#fff; border:none;">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}" style="background:#d0dcad; color:#16726d; border:none;">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="background:#16726d; color:#fff; border:none;">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link" style="background:#d0dcad; color:#16726d; border:none;">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
