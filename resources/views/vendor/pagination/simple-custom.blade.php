@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;margin-top:20px;padding:4px 0;">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="padding:8px 16px;display:flex;align-items:center;gap:6px;border-radius:10px;background:#f1f5f9;color:#94a3b8;font-size:13px;font-weight:600;cursor:not-allowed;">
            <i class="fas fa-chevron-left"></i> Sebelumnya
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="padding:8px 16px;display:flex;align-items:center;gap:6px;border-radius:10px;background:#f1f5f9;color:#475569;font-size:13px;font-weight:600;text-decoration:none;transition:.15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            <i class="fas fa-chevron-left"></i> Sebelumnya
        </a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="padding:8px 16px;display:flex;align-items:center;gap:6px;border-radius:10px;background:linear-gradient(135deg,#1db349,#a5cf36);color:#fff;font-size:13px;font-weight:700;text-decoration:none;transition:.15s;box-shadow:0 2px 8px rgba(29,179,73,0.25);">
            Selanjutnya <i class="fas fa-chevron-right"></i>
        </a>
    @else
        <span style="padding:8px 16px;display:flex;align-items:center;gap:6px;border-radius:10px;background:#f1f5f9;color:#94a3b8;font-size:13px;font-weight:600;cursor:not-allowed;">
            Selanjutnya <i class="fas fa-chevron-right"></i>
        </span>
    @endif
</nav>
@endif
