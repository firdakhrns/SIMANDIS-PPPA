@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-navy">Registrasi Surat Undangan Masuk</h2>
        <p class="text-xs text-slate-400 mt-1">Input data awal surat masuk untuk mading agenda internal.</p>
    </div>

    <form method="POST" action="{{ route('agenda.store') }}" class="space-y-4 text-xs">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Nomor Surat</label>
                <input type="text" name="no_surat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Nomor Agenda</label>
                <input type="text" name="no_agenda" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Tanggal Surat</label>
                <input type="date" name="tgl_surat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Tanggal Diterima</label>
                <input type="date" name="tgl_diterima" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Surat Dari / Pengirim</label>
                <input type="text" name="surat_dari" required placeholder="misal: ULM Banjarmasin" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Sifat Surat</label>
                <select name="sifat_surat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                    <option value="Segera">Segera</option>
                    <option value="Sangat Segera">Sangat Segera</option>
                    <option value="Rahasia">Rahasia</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Perihal / Nama Agenda</label>
            <input type="text" name="perihal" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Diteruskan ke Bidang Penanggung Jawab</label>
            <select name="bidang_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white">
                <option value="1">Bidang 1: Perlindungan Khusus Anak (PKA)</option>
                <option value="2">Bidang 2: Perlindungan Perempuan (PP)</option>
                <option value="3">Bidang 3: Pemenuhan Hak Anak (PHA)</option>
                <option value="4">Bidang 4: Kualitas Hidup Perempuan (KHP)</option>
            </select>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('mading.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-navy text-white font-semibold shadow-md">Simpan Agenda</button>
        </div>
    </form>
</div>
@endsection