@extends('layouts.app')

@section('title', 'Penilaian Esai — ' . $round->name)
@section('page-title', 'Penilaian Esai: ' . $round->name)

@section('content')
<a href="{{ route('organizer.events.show', $round->event) }}" class="btn btn-secondary btn-sm mb-6"><i class="fas fa-arrow-left"></i> Kembali</a>

@if($sessions->isEmpty())
    <div class="card"><div class="empty-state"><i class="fas fa-check-double"></i><p>Tidak ada esai yang perlu dinilai.</p></div></div>
@else
    <div class="flex justify-between items-center mb-4">
        <p style="font-size:12px;color:var(--color-text-tertiary)">{{ $sessions->count() }} sesi dengan esai belum dinilai</p>
        <form method="POST" action="{{ route('organizer.grading.publish', $round) }}">
            @csrf
            <button class="btn btn-success btn-sm"><i class="fas fa-bullhorn"></i> Publish Semua yang Sudah Dinilai</button>
        </form>
    </div>

    @foreach($sessions as $session)
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user" style="color:var(--color-accent)"></i>
                {{ $session->participant->user->name }}
                <span style="font-size:11px;color:var(--color-text-tertiary);font-weight:500;margin-left:8px">{{ $session->participant->participant_code }}</span>
            </h3>
            <span class="badge badge-info">PG: {{ $session->score_pg }}</span>
        </div>

        @foreach($session->answers as $answer)
        @if($answer->question->type === 'essay')
        <div style="padding:20px;margin-bottom:20px;background:var(--color-surface-soft);border-radius:16px;border:1px solid var(--color-border);box-shadow:0 4px 12px rgba(0,0,0,.02);">
            <p style="font-size:11px;color:var(--color-primary);font-weight:800;letter-spacing:0.5px;margin-bottom:8px;text-transform:uppercase;">SOAL (skor maks: {{ $answer->question->score }})</p>
            <p style="font-size:14px;font-weight:700;color:var(--color-text-primary);margin-bottom:20px;line-height:1.6">{{ $answer->question->content }}</p>

            <p style="font-size:11px;color:var(--color-text-tertiary);font-weight:800;letter-spacing:0.5px;margin-bottom:6px;text-transform:uppercase;">JAWABAN PESERTA:</p>
            <div style="padding:16px;background:#fff;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:20px;font-size:14px;color:var(--color-text-secondary);line-height:1.7;white-space:pre-wrap;">{{ $answer->essay_answer ?? '(Tidak dijawab)' }}</div>

            @if($answer->isGraded())
                <div class="flex items-center gap-4">
                    <span class="badge badge-success">✓ Dinilai: {{ $answer->score }}</span>
                    @if($answer->essay_feedback)
                    <span style="font-size:11px;color:var(--color-text-tertiary)">Feedback: {{ $answer->essay_feedback }}</span>
                    @endif
                </div>
            @else
                <form method="POST" action="{{ route('organizer.grading.grade', $answer) }}" class="flex items-center gap-4">
                    @csrf
                    <div class="form-group" style="margin-bottom:0;flex:0 0 120px;">
                        <input type="number" name="score" class="form-input" placeholder="Skor" min="0" max="{{ $answer->question->score }}" step="0.5" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;flex:1;">
                        <input type="text" name="feedback" class="form-input" placeholder="Feedback (opsional)">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Nilai</button>
                </form>
            @endif
        </div>
        @endif
        @endforeach
    </div>
    @endforeach
@endif
@endsection
