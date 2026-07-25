@extends('layouts.app')

@section('title', 'Bank Soal — ' . $event->name)
@section('page-title', 'Bank Soal: ' . $event->name)

@section('content')
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Tambah Bank Soal</h3>
    </div>
    <form method="POST" action="{{ route('organizer.questions.banks.store', $event) }}" class="grid grid-3">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Bank</label>
            <input type="text" name="name" class="form-input" required placeholder="Contoh: Soal Penyisihan">
        </div>
        <div class="form-group">
            <label class="form-label">Subjek</label>
            <input type="text" name="subject" class="form-input" placeholder="Sejarah Islam">
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Buat</button>
        </div>
    </form>
</div>

@if($banks->isEmpty())
    <div class="card"><div class="empty-state"><i class="fas fa-list-ul"></i><p>Belum ada bank soal.</p></div></div>
@else
    <div class="grid grid-3">
        @foreach($banks as $bank)
        <div class="card">
            <h3 style="font-size:14px;font-weight:700;margin-bottom:4px;">{{ $bank->name }}</h3>
            <p style="font-size:11px;color:var(--color-text-tertiary);margin-bottom:16px;">{{ $bank->subject ?? 'Umum' }}</p>
            <div class="stat-card" style="margin-bottom:16px;">
                <span class="stat-value">{{ $bank->questions_count }}</span>
                <span class="stat-label">Soal</span>
            </div>
            <a href="{{ route('organizer.questions.bank.show', $bank) }}" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                <i class="fas fa-pencil"></i> Kelola Soal
            </a>
        </div>
        @endforeach
    </div>
@endif
@endsection
