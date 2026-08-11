@extends('layouts.app')

@section('content')

@php
    $surat = $agenda->surat;
    $disposisi = $agenda->disposisi;

    $suratDari = $surat->surat_dari ?? $agenda->surat_dari ?? '-';
    $perihal = $surat->perihal ?? $agenda->perihal ?? '-';
    $noSurat = $surat->no_surat ?? $agenda->no_surat ?? '-';
    $tglSurat = $surat->tgl_surat ?? $agenda->tgl_surat ?? now();

    $statusDisposisi = $disposisi->status_disposisi ?? 'Disposisi';

    $rawTarget = $disposisi->diteruskan_kepada ?? '[]';
    $selectedTarget = is_array($rawTarget) 
        ? $rawTarget 
        : json_decode($rawTarget ?? '[]', true);
    $selectedTarget = $selectedTarget ?? [];
@endphp

<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative">

    <div class="border-b border-slate-200 pb-4 mb-6 text-center pr-8">
        <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide">Pemerintah Kota Banjarmasin</h2>
        <h3 class="text-sm font-extrabold text-navy uppercase">Dinas Pemberdayaan Perempuan dan Perlindungan Anak</h3>
        <p class="text-xs font-bold text-slate-500 mt-2 underline">LEMBAR DISPOSISI KEPALA DINAS</p>
    </div>

    <form method="POST" action="{{ route('disposisi.update', $agenda->id) }}" class="space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1">
            <p><span class="font-bold text-slate-600">Surat Dari:</span> {{ $suratDari }}</p>
            <p><span class="font-bold text-slate-600">Perihal:</span> {{ $perihal }}</p>
            <p><span class="font-bold text-slate-600">No / Tgl Surat:</span> {{ $noSurat }} / {{ \Carbon\Carbon::parse($tglSurat)->locale('id')->translatedFormat('d F Y') }}</p>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Status Kehadiran Kadis</label>
            <select name="status_disposisi" id="statusKehadiranSelect" onchange="toggleDisposisiFields()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white font-medium focus:ring-2 focus:ring-navy">
                <option value="Hadir" {{ old('status_disposisi', $statusDisposisi) === 'Hadir' ? 'selected' : '' }}>Hadir Langsung</option>
                <option value="Disposisi" {{ old('status_disposisi', $statusDisposisi) === 'Disposisi' ? 'selected' : '' }}>Disposisi / Diwakilkan</option>
            </select>
        </div>

        <div id="containerDiteruskan" class="transition-all duration-200">
            <label class="block font-bold text-slate-700 mb-2">Diteruskan Kepada Sdr.:</label>
            <div class="grid grid-cols-2 gap-2">
                <label class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Sekretaris" {{ in_array('Sekretaris', old('diteruskan_kepada', $selectedTarget)) ? 'checked' : '' }} class="cb-diteruskan rounded text-navy focus:ring-navy"> Sekretaris
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid PKA" {{ in_array('Kabid PKA', old('diteruskan_kepada', $selectedTarget)) ? 'checked' : '' }} class="cb-diteruskan rounded text-navy focus:ring-navy"> Kabid Perlindungan Khusus Anak
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid PP" {{ in_array('Kabid PP', old('diteruskan_kepada', $selectedTarget)) ? 'checked' : '' }} class="cb-diteruskan rounded text-navy focus:ring-navy"> Kabid Perlindungan Perempuan
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid PHA" {{ in_array('Kabid PHA', old('diteruskan_kepada', $selectedTarget)) ? 'checked' : '' }} class="cb-diteruskan rounded text-navy focus:ring-navy"> Kabid Pemenuhan Hak Anak
                </label>
                <label class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="diteruskan_kepada[]" value="Kabid KHP" {{ in_array('Kabid KHP', old('diteruskan_kepada', $selectedTarget)) ? 'checked' : '' }} class="cb-diteruskan rounded text-navy focus:ring-navy"> Kabid Kualitas Hidup Perempuan
                </label>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">
                Catatan Arahan Kepala Dinas <span class="text-rose-500">*</span>
            </label>
            
            <textarea 
                name="catatan_kadis" 
                rows="4" 
                required 
                placeholder="Masukkan petunjuk / instruksi disposisi Kepala Dinas di sini..."
                class="w-full px-3.5 py-2.5 rounded-xl border @error('catatan_kadis') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-navy @enderror text-xs focus:outline-none"
            >{{ old('catatan_kadis', $disposisi->catatan_kadis ?? '') }}</textarea>

            @error('catatan_kadis')
                <p class="text-[11px] font-bold text-rose-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold hover:bg-slate-200 transition-all">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-navy text-white font-semibold shadow-md hover:bg-blue-900 transition-all">Simpan Disposisi</button>
        </div>
    </form>
</div>

<script>
    function toggleDisposisiFields() {
        const select = document.getElementById('statusKehadiranSelect');
        const container = document.getElementById('containerDiteruskan');
        const checkboxes = document.querySelectorAll('.cb-diteruskan');

        if (select.value === 'Hadir') {
            container.classList.add('opacity-40', 'pointer-events-none', 'select-none');
            
            checkboxes.forEach(cb => {
                cb.checked = false;
                cb.disabled = true;
            });
        } else {
            container.classList.remove('opacity-40', 'pointer-events-none', 'select-none');
            
            checkboxes.forEach(cb => {
                cb.disabled = false;
            });
        }
    }

    document.addEventListener('DOMContentLoaded', toggleDisposisiFields);
</script>
@endsection