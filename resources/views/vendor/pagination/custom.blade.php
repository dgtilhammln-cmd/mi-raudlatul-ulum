@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;margin-top:20px;padding:4px 0;">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#f1f5f9;color:#94a3b8;font-size:13px;cursor:not-allowed;">
            <i class="fas fa-chevron-left"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#f1f5f9;color:#475569;font-size:13px;text-decoration:none;transition:.15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-chevron-left"></i>
        </a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:10px;color:#94a3b8;font-size:13px;font-weight:600;">…</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:linear-gradient(135deg,#1db349,#a5cf36);color:#fff;font-size:13px;font-weight:700;box-shadow:0 2px 8px rgba(29,179,73,0.3);">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#f1f5f9;color:#475569;font-size:13px;font-weight:600;text-decoration:none;transition:.15s;" onmouseover="this.style.background='#dcfce7';this.style.color='#16a34a'" onmouseout="this.style.background='#f1f5f9';this.style.color='#475569'">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#f1f5f9;color:#475569;font-size:13px;text-decoration:none;transition:.15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-chevron-right"></i>
        </a>
    @else
        <span style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#f1f5f9;color:#94a3b8;font-size:13px;cursor:not-allowed;">
            <i class="fas fa-chevron-right"></i>
        </span>
    @endif
</nav>
<p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:8px;">
    Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
</p>
@endif
