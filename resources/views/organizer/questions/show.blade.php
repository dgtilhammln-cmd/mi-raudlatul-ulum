@extends('layouts.app')

@section('title', 'Bank: ' . $bank->name)
@section('page-title', 'Bank Soal: ' . $bank->name)

@section('content')
<a href="{{ route('organizer.questions.index', $bank->event) }}" class="btn btn-secondary btn-sm mb-6"><i class="fas fa-arrow-left"></i> Kembali</a>

{{-- Form Import Excel --}}
<div class="card mb-6">
    <div class="card-header"><h3 class="card-title" style="color:var(--color-info)"><i class="fas fa-file-excel"></i> Import Soal via Excel</h3></div>
    <div style="margin-bottom:20px;">
        <p style="font-size:13px;color:var(--color-text-secondary);margin-bottom:12px;">Pastikan file Excel Anda memiliki <strong>baris header (baris pertama)</strong> dengan nama kolom persis seperti berikut:</p>
        <div class="table-wrapper" style="border:1px solid rgba(0,0,0,0.08);border-radius:var(--radius-sm);background:#fff;overflow-x:auto;">
            <table style="width:100%;text-align:left;font-size:12px;white-space:nowrap;">
                <thead style="background:var(--color-info-light, #e0f2fe);color:var(--color-text-primary);">
                    <tr>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">jenis</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">isi_soal</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">opsi_a</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">opsi_b</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">opsi_c</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">opsi_d</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">opsi_e</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">jawaban_benar</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">pembahasan</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">skor_benar</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">skor_salah</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">tingkat_kesulitan</th>
                        <th style="padding:10px 14px;border-bottom:1px solid #bae6fd;">kategori</th>
                    </tr>
                </thead>
                <tbody style="color:var(--color-text-secondary);">
                    <tr>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">multiple_choice</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Siapakah khalifah pertama?</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Abu Bakar</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Umar bin Khattab</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Utsman</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Ali bin Abi Thalib</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Muawiyah</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">a</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Abu Bakar as-Siddiq...</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">4</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">-1</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">easy</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">Khulafaur Rasyidin</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ul style="font-size:12px;color:var(--color-text-tertiary);margin-top:12px;padding-left:16px;line-height:1.8;">
            <li><strong>jenis:</strong> <code>multiple_choice</code> atau <code>essay</code>.</li>
            <li><strong>jawaban_benar:</strong> isi dengan huruf pilihan (<code>a</code>, <code>b</code>, <code>c</code>, <code>d</code>, atau <code>e</code>). Untuk esai, kosongkan.</li>
            <li><strong>tingkat_kesulitan:</strong> <code>easy</code>, <code>medium</code>, atau <code>hard</code>.</li>
        </ul>
    </div>
    <form method="POST" action="{{ route('organizer.questions.import', $bank) }}" enctype="multipart/form-data" class="flex gap-4 items-center">
        @csrf
        <input type="file" name="file" class="form-input" accept=".xlsx,.xls,.csv" required style="max-width:300px;padding:8px">
        <button type="submit" class="btn btn-info" style="background:var(--color-info);color:#fff;"><i class="fas fa-upload"></i> Proses Import</button>
    </form>
</div>

{{-- Form Tambah Soal Manual --}}
<div class="card mb-6">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-plus-circle" style="color:var(--color-accent)"></i> Tambah Soal</h3></div>
    <form method="POST" action="{{ route('organizer.questions.store', $bank) }}" id="formSoal">
        @csrf
        <div class="grid grid-2">
            <div class="form-group">
                <label class="form-label">Tipe Soal</label>
                <select name="type" class="form-select" id="questionType" onchange="toggleOptions()">
                    <option value="multiple_choice">Pilihan Ganda</option>
                    <option value="essay">Esai</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kesulitan</label>
                <select name="difficulty" class="form-select">
                    <option value="easy">Mudah</option>
                    <option value="medium" selected>Sedang</option>
                    <option value="hard">Sulit</option>
                </select>
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label class="form-label">Pertanyaan</label>
                <textarea name="content" class="form-textarea" rows="3" required placeholder="Tulis soal di sini..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Skor Benar</label>
                <input type="number" name="score" class="form-input" value="10" step="0.5" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">Skor Salah (negatif)</label>
                <input type="number" name="negative_score" class="form-input" value="0" step="0.5" min="0">
            </div>
            <div class="form-group" style="grid-column:1/-1">
                <label class="form-label">Penjelasan (opsional)</label>
                <textarea name="explanation" class="form-textarea" rows="2" placeholder="Pembahasan jawaban..."></textarea>
            </div>
        </div>

        {{-- Options (PG) --}}
        <div id="optionsSection">
            <h4 style="font-size:12px;font-weight:700;color:var(--color-accent);margin-bottom:12px;margin-top:8px">OPSI JAWABAN</h4>
            <div id="optionsList">
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-2 mb-4" style="padding:12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                    <input type="radio" name="options[{{ $i }}][is_correct]" value="1" style="accent-color:var(--color-primary);min-width:18px;">
                    <input type="text" name="options[{{ $i }}][content]" class="form-input" placeholder="Opsi {{ chr(65+$i) }}" style="flex:1;">
                </div>
                @endfor
            </div>
            <p style="font-size:10px;color:var(--color-text-tertiary)"><i class="fas fa-info-circle"></i> Pilih radio button untuk menandai jawaban benar.</p>
        </div>

        <button type="submit" class="btn btn-primary mt-4"><i class="fas fa-save"></i> Simpan Soal</button>
    </form>
</div>

{{-- Daftar Soal --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Soal ({{ $bank->questions->count() }})</h3>
    </div>

    @if($bank->questions->isEmpty())
        <div class="empty-state"><i class="fas fa-question-circle"></i><p>Belum ada soal di bank ini.</p></div>
    @else
        @foreach($bank->questions as $i => $q)
        <div style="padding:16px;margin-bottom:12px;background:#fff;border-radius:12px;border:1px solid rgba(0,0,0,0.08);box-shadow:0 2px 8px rgba(0,0,0,0.02);">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <span style="background:var(--color-primary);color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;">{{ $i+1 }}</span>
                    <span class="badge badge-{{ $q->type=='multiple_choice'?'info':'warning' }}">{{ $q->type=='multiple_choice'?'PG':'Esai' }}</span>
                    <span class="badge badge-{{ $q->difficulty=='easy'?'success':($q->difficulty=='hard'?'danger':'warning') }}">{{ $q->difficulty }}</span>
                    <span style="font-size:11px;color:var(--color-text-tertiary)">Skor: {{ $q->score }}</span>
                </div>
                <form method="POST" action="{{ route('organizer.questions.destroy', $q) }}" data-confirm="Hapus soal ini?">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </form>
            </div>
            <p style="font-size:13px;font-weight:600;line-height:1.6;margin-bottom:12px;">{{ $q->content }}</p>
            @if($q->options->isNotEmpty())
                @foreach($q->options as $opt)
                <div style="padding:6px 12px;font-size:12px;display:flex;align-items:center;gap:8px;{{ $opt->is_correct?'color:var(--color-success);font-weight:700;':'color:var(--color-text-secondary);' }}">
                    <i class="fas {{ $opt->is_correct ? 'fa-check-circle' : 'fa-circle' }}" style="font-size:10px;"></i>
                    {{ $opt->content }}
                </div>
                @endforeach
            @endif
        </div>
        @endforeach
    @endif
</div>

@push('scripts')
<script>
function toggleOptions() {
    const type = document.getElementById('questionType').value;
    document.getElementById('optionsSection').style.display = type === 'multiple_choice' ? 'block' : 'none';
}
</script>
@endpush
@endsection
