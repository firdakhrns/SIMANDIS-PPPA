@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <div class="border-b border-slate-200 pb-4 mb-6 text-center">
        <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide">Pemerintah Kota Banjarmasin</h2>
        <h3 class="text-sm font-extrabold text-navy uppercase">Dinas Pemberdayaan Perempuan dan Perlindungan Anak</h3>
        <p class="text-xs font-bold text-slate-500 mt-2 underline">LEMBAR DISPOSISI KEPALA DINAS</p>
    </div>

    <form method="POST" action="{{ route('disposisi.update', $agenda->id) }}" class="space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1">
            <p><span class="font-bold text-slate-600">Surat Dari:</span> {{ $agenda->surat_dari }}</p>
            <p><span class="font-bold text-slate-600">Perihal:</span> {{ $agenda->perihal }}</p>
            <p><span class="font-bold text-slate-600">No / Tgl Surat:</span> {{ $agenda->no_surat }} / {{ $agenda->tgl_surat }}</p>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Status Kehadiran Kadis</label>
            <select name="status_disposisi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white">
                <option value="Hadir" {{ $agenda->status_disposisi === 'Hadir' ? 'selected' : '' }}>Hadir Langsung</option>
                <option value="Disposisi" {{ $agenda->status_disposisi === 'Disposisi' ? 'selected' : '' }}>Disposisi / Diwakilkan</option>
            </select>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-2">Diteruskan Kepada Sdr.:</label>
            <div class="grid grid-cols-2 gap-2">
                <label class="flex items-center gap-2 bg-slate-50 p-2 rounded-lg border">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Sekretaris"> Sekretaris
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2 rounded-lg border">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid PKA"> Kabid Perlindungan Khusus Anak
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2 rounded-lg border">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid PP"> Kabid Perlindungan Perempuan
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2 rounded-lg border">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid PHA"> Kabid Pemenuhan Hak Anak
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2 rounded-lg border">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid KHP"> Kabid Kualitas Hidup Perempuan
                </label>
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Catatan Arahan Kepala Dinas:</label>
            <textarea name="catatan_kadis" rows="3" class="w-full p-3 rounded-xl border border-slate-200" placeholder="Tulis petunjuk/arahan tertulis..."></textarea>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('mading.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-navy text-white font-semibold shadow-md">Simpan Disposisi</button>
        </div>
    </form>
</div>
@endsection