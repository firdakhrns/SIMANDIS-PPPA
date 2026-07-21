@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-navy">Laporan Realisasi Kegiatan Field</h2>
        <p class="text-xs text-slate-400 mt-1">Upload bukti pelaksanaan kegiatan di lapangan pasca-acara.</p>
    </div>

    <form method="POST" action="{{ route('realisasi.store', $agenda->id) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
        @csrf

        <div class="bg-blue-50 p-3 rounded-xl text-navy">
            <p class="font-bold">{{ $agenda->perihal }}</p>
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Jumlah Peserta (Total)</label>
            <input type="number" name="jumlah_peserta" min="0" required placeholder="misal: 45" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">File Surat Tugas (PDF)</label>
            <input type="file" name="file_surat_tugas" accept=".pdf" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Foto Dokumentasi Kegiatan (Bisa pilih beberapa)</label>
            <input type="file" name="foto_dokumentasi[]" accept="image/*" multiple required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('mading.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold shadow-md">Simpan Laporan</button>
        </div>
    </form>
</div>
@endsection