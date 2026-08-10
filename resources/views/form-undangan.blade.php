@extends('layouts.app')

@section('content')

@php
    $isEdit = isset($agenda);
@endphp

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
    <a href="{{ route('mading.index') }}" class="hover:text-navy">
        <i class="fa-solid fa-house"></i> Mading Utama
    </a>
    <i class="fa-solid fa-chevron-right text-[10px]"></i>
    <span class="font-bold text-navy">{{ $isEdit ? 'Edit Agenda Kegiatan' : 'Surat Undangan Masuk' }}</span>
</div>

<!-- Card Form Utama -->
<div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl border border-slate-100 shadow-xs">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-base font-bold text-navy">{{ $isEdit ? 'Edit Data Surat Undangan Agenda' : 'Surat Undangan Masuk' }}</h2>
        <p class="text-xs text-slate-400 mt-1">
            {{ $isEdit ? 'Perbarui data agenda kegiatan yang sudah terdaftar.' : 'Input data awal surat masuk untuk mading agenda internal.' }}
        </p>
    </div>

    {{-- Pesan Alert Jika Validasi Controller Gagal --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs">
            <p class="font-bold mb-1">Terjadi kesalahan input:</p>
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('agenda.update', $agenda->id) : route('agenda.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- Auto generate no_agenda jika baru --}}
        <input type="hidden" name="no_agenda" value="{{ old('no_agenda', $agenda->no_agenda ?? 'AGD-' . time()) }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nomor Surat <span class="text-rose-500">*</span></label>
                <input type="text" name="no_surat" value="{{ old('no_surat', $agenda->no_surat ?? '') }}" required placeholder="Contoh: 001/PPPA/PKA/2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-navy">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">No Agenda</label>
                <input type="number" name="no_agenda" value="{{ old('no_agenda', $agenda->no_agenda ?? '') }}" placeholder="Masukkan No Agenda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-navy">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_surat_date" value="{{ old('tgl_surat_date', isset($agenda) ? \Carbon\Carbon::parse($agenda->tgl_surat)->format('Y-m-d') : '') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-navy">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Diterima <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_diterima" value="{{ old('tgl_diterima', $agenda->tgl_diterima ?? date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-navy">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jam Kegiatan <span class="text-rose-500">*</span></label>
                <input type="time" name="tgl_surat_time" value="{{ old('tgl_surat_time', isset($agenda) ? \Carbon\Carbon::parse($agenda->tgl_surat)->format('H:i') : '08:30') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-navy">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Surat Dari / Pengirim <span class="text-rose-500">*</span></label>
                <input type="text" name="surat_dari" value="{{ old('surat_dari', $agenda->surat_dari ?? '') }}" required placeholder="misal: ULM Banjarmasin" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-navy">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Sifat Surat <span class="text-rose-500">*</span></label>
            <select name="sifat_surat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-navy">
                <option value="Segera" {{ old('sifat_surat', $agenda->sifat_surat ?? '') == 'Segera' ? 'selected' : '' }}>Segera</option>
                <option value="Sangat Segera" {{ old('sifat_surat', $agenda->sifat_surat ?? '') == 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                <option value="Rahasia" {{ old('sifat_surat', $agenda->sifat_surat ?? '') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
            </select>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Perihal / Nama Agenda <span class="text-rose-500">*</span></label>
            <textarea name="perihal" rows="3" required placeholder="Deskripsikan inti agenda atau perihal surat di sini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-navy">{{ old('perihal', $agenda->perihal ?? '') }}</textarea>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">File Surat Undangan (PDF / Word)</label>
            <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center bg-slate-50/50 hover:bg-slate-50 transition-colors">
                <input type="file" name="file_pdf" accept=".pdf,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="showFileName(this)">
                <i class="fa-solid fa-file-pdf text-3xl text-navy mb-2"></i>
                <p id="fileNameText" class="font-bold text-slate-700 text-xs">
                    @if(isset($agenda) && $agenda->file_pdf)
                        File Saat Ini: <span class="text-navy">{{ $agenda->file_pdf }}</span> (Klik untuk mengganti)
                    @else
                        Tarik file ke sini atau klik untuk unggah
                    @endif
                </p>
                <p class="text-[10px] text-slate-400 mt-0.5">.pdf, .doc, .docx (Maksimal file size 50MB)</p>
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Diteruskan ke Bidang Penanggung Jawab <span class="text-rose-500">*</span></label>
            <select name="bidang_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-navy">
                <option value="1" {{ old('bidang_id', $agenda->bidang_id ?? '') == 1 ? 'selected' : '' }}>Bidang Perlindungan Khusus Anak (PKA)</option>
                <option value="2" {{ old('bidang_id', $agenda->bidang_id ?? '') == 2 ? 'selected' : '' }}>Bidang Perlindungan Perempuan (PP)</option>
                <option value="3" {{ old('bidang_id', $agenda->bidang_id ?? '') == 3 ? 'selected' : '' }}>Bidang Pemenuhan Hak Anak (PHA)</option>
                <option value="4" {{ old('bidang_id', $agenda->bidang_id ?? '') == 4 ? 'selected' : '' }}>Bidang Kualitas Hidup Perempuan (KHP)</option>
            </select>
        </div>

        @php
            $isEdit = isset($agenda);
            $backRoute = Auth::user()->role === 'admin' ? route('mading.index') : route('mading.bidang');
            $backTitle = Auth::user()->role === 'admin' ? 'Mading Utama' : 'Mading Bidang';
        @endphp

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ $backRoute }}" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-navy text-white font-bold shadow-md hover:bg-blue-900 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-bookmark"></i> {{ $isEdit ? 'Perbarui Agenda' : 'Simpan Agenda' }}
            </button>
        </div>
    </form>
</div>

<script>
    function showFileName(input) {
        const textLabel = document.getElementById('fileNameText');
        if (input.files && input.files[0]) {
            textLabel.innerText = "File Terpilih: " + input.files[0].name;
            textLabel.classList.add('text-navy');
        }
    }
</script>

@endsection