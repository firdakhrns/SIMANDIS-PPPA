@extends('layouts.app')

@section('content')

@php
    $isEdit = isset($agenda);
    $actionUrl = $isEdit ? route('agenda.update', $agenda->id) : route('agenda.store');
    
    $surat = $agenda->surat ?? null;
    $noSurat = old('no_surat', $surat->no_surat ?? '');
    $noAgenda = old('no_agenda', $agenda->no_agenda ?? 'AGD-' . time());
    $suratDari = old('surat_dari', $surat->surat_dari ?? '');
    $perihal = old('perihal', $surat->perihal ?? '');
    $sifatSurat = old('sifat_surat', $surat->sifat_surat ?? 'Segera');
    $lokasi = old('lokasi', $agenda->lokasi ?? '');
    
    $tglSuratDate = old('tgl_surat_date', $surat ? \Carbon\Carbon::parse($surat->tgl_surat)->format('Y-m-d') : date('Y-m-d'));
    $tglDiterima = old('tgl_diterima', $surat->tgl_diterima ?? date('Y-m-d'));
    $tglKegiatanDate = old('tgl_kegiatan_date', $agenda->tgl_kegiatan ?? date('Y-m-d'));
    $jamKegiatan = old('tgl_surat_time', $agenda->jam_kegiatan ?? '08:30');
    $bidangId = old('bidang_id', $agenda->bidang_id ?? request('bidang', 1));
@endphp

@if ($errors->any())
    <div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-xl text-rose-700 text-xs shadow-xs">
        <p class="font-bold mb-1"><i class="fa-solid fa-triangle-exclamation"></i> Gagal Menyimpan Data Agenda:</p>
        <ul class="list-disc pl-4 space-y-1 font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <h2 class="text-xl font-bold text-[#1a2b4c]">
                {{ $isEdit ? 'Edit Data Surat Undangan Agenda' : 'Registrasi Agenda Kegiatan Baru' }}
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">
                {{ $isEdit ? 'Perbarui data agenda kegiatan yang sudah terdaftar.' : 'Isi formulir untuk menambahkan jadwal agenda baru.' }}
            </p>
        </div>
        <a href="{{ Auth::user()->role === 'admin' ? route('mading.index') : route('mading.bidang') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ $actionUrl }}" enctype="multipart/form-data" class="space-y-4 text-xs">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        @if(Auth::user()->role === 'admin')
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Bidang Dituju <span class="text-rose-500">*</span></label>
                <select name="bidang_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#1a2b4c]">
                    <option value="">-- Pilih Bidang --</option>
                    <option value="1" {{ $bidangId == 1 ? 'selected' : '' }}>Bidang Perlindungan Khusus Anak (PKA)</option>
                    <option value="2" {{ $bidangId == 2 ? 'selected' : '' }}>Bidang Perlindungan Perempuan (PP)</option>
                    <option value="3" {{ $bidangId == 3 ? 'selected' : '' }}>Bidang Pemenuhan Hak Anak (PHA)</option>
                    <option value="4" {{ $bidangId == 4 ? 'selected' : '' }}>Bidang Kualitas Hidup Perempuan (KHP)</option>
                </select>
            </div>
        @else
            <input type="hidden" name="bidang_id" value="{{ Auth::user()->bidang_id ?? $bidangId }}">
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nomor Surat <span class="text-rose-500">*</span></label>
                <input type="text" name="no_surat" value="{{ $noSurat }}" minlength="3" maxlength="50" required placeholder="Contoh: 001/PPPA/PKA/2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">No Agenda <span class="text-rose-500">*</span></label>
                <input type="text" name="no_agenda" value="{{ $noAgenda }}" required placeholder="Masukkan No Agenda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_surat_date" value="{{ $tglSuratDate }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Diterima <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_diterima" value="{{ $tglDiterima }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Pelaksanaan Kegiatan <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_kegiatan_date" value="{{ $tglKegiatanDate }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jam Kegiatan <span class="text-rose-500">*</span></label>
                <input type="time" name="tgl_surat_time" value="{{ $jamKegiatan }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Surat Dari / Pengirim <span class="text-rose-500">*</span></label>
                <input type="text" name="surat_dari" value="{{ $suratDari }}" minlength="3" maxlength="255" required placeholder="misal: ULM Banjarmasin" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tempat / Lokasi Kegiatan (Opsional)</label>
                <input type="text" name="lokasi" value="{{ $lokasi }}" placeholder="misal: Aula Lantai 3 DPPPA" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Sifat Surat <span class="text-rose-500">*</span></label>
            <select name="sifat_surat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#1a2b4c]">
                <option value="Segera" {{ $sifatSurat === 'Segera' ? 'selected' : '' }}>Segera</option>
                <option value="Sangat Segera" {{ $sifatSurat === 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                <option value="Rahasia" {{ $sifatSurat === 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
            </select>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Perihal / Nama Agenda <span class="text-rose-500">*</span></label>
            <textarea name="perihal" rows="3" minlength="5" maxlength="150" required placeholder="Deskripsikan inti agenda atau perihal surat di sini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">{{ $perihal }}</textarea>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">File Surat Undangan (PDF / Word) {{ $isEdit ? '' : '*' }}</label>
            <input type="file" name="file_pdf" accept=".pdf,.doc,.docx" {{ $isEdit ? '' : 'required' }} class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none">
            @if($surat && $surat->file_pdf)
                <p class="text-[11px] text-slate-400 mt-1">File saat ini: <span class="font-bold text-[#1a2b4c]">{{ $surat->file_pdf }}</span></p>
            @endif
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ Auth::user()->role === 'admin' ? route('mading.index') : route('mading.bidang') }}" 
            class="px-5 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-[#1a2b4c] text-white font-bold text-xs rounded-xl hover:bg-blue-900 transition-colors shadow-xs">
                {{ isset($agenda) ? 'Perbarui Data Agenda' : 'Simpan Agenda' }}
            </button>
        </div>
    </form>
</div>

@endsection