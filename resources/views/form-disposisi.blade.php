@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative">

    <!-- Header Lembar Disposisi -->
    <div class="border-b border-slate-200 pb-4 mb-6 text-center pr-8">
        <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide">Pemerintah Kota Banjarmasin</h2>
        <h3 class="text-sm font-extrabold text-navy uppercase">Dinas Pemberdayaan Perempuan dan Perlindungan Anak</h3>
        <p class="text-xs font-bold text-slate-500 mt-2 underline">LEMBAR DISPOSISI KEPALA DINAS</p>
    </div>

    <form method="POST" action="{{ route('disposisi.update', $agenda->id) }}" class="space-y-4 text-xs">
        @csrf
        @method('PUT')

        <!-- Ringkasan Info Agenda -->
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1">
            <p><span class="font-bold text-slate-600">Surat Dari:</span> {{ $agenda->surat_dari }}</p>
            <p><span class="font-bold text-slate-600">Perihal:</span> {{ $agenda->perihal }}</p>
            <p><span class="font-bold text-slate-600">No / Tgl Surat:</span> {{ $agenda->no_surat }} / {{ \Carbon\Carbon::parse($agenda->tgl_surat)->locale('id')->translatedFormat('d F Y') }}</p>
        </div>

        <!-- Status Kehadiran Kadis -->
        <div>
            <label class="block font-bold text-slate-700 mb-1">Status Kehadiran Kadis</label>
            {{-- Tambahkan id="statusKehadiranSelect" dan event onchange --}}
            <select name="status_disposisi" id="statusKehadiranSelect" onchange="toggleDisposisiFields()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white font-medium focus:ring-2 focus:ring-navy">
                <option value="Hadir" {{ old('status_disposisi', $agenda->status_disposisi) === 'Hadir' ? 'selected' : '' }}>Hadir Langsung</option>
                <option value="Disposisi" {{ old('status_disposisi', $agenda->status_disposisi) === 'Disposisi' ? 'selected' : '' }}>Disposisi / Diwakilkan</option>
            </select>
        </div>

        <!-- Checkbox Diteruskan Kepada -->
        @php
            $selectedTarget = is_array($agenda->diteruskan_kepada) 
                ? $agenda->diteruskan_kepada 
                : json_decode($agenda->diteruskan_kepada ?? '[]', true);
            $selectedTarget = $selectedTarget ?? [];
        @endphp
        
        {{-- Tambahkan id="containerDiteruskan" di pembungkus ini --}}
        <div id="containerDiteruskan" class="transition-all duration-200">
            <label class="block font-bold text-slate-700 mb-2">Diteruskan Kepada Sdr.:</label>
            <div class="grid grid-cols-2 gap-2">
                {{-- Tambahkan class cb-diteruskan pada masing-masing input checkbox --}}
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

        <!-- Textarea Catatan Kadis -->
        <div>
            <label class="block font-bold text-slate-700 mb-1">Catatan Arahan Kepala Dinas:</label>
            <textarea 
                name="catatan_kadis" 
                rows="3" 
                class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-navy text-slate-800" 
                placeholder="Tulis petunjuk/arahan tertulis..."
            >{{ old('catatan_kadis', $agenda->catatan_kadis ?? '') }}</textarea>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('mading.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-semibold hover:bg-slate-200 transition-all">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-navy text-white font-semibold shadow-md hover:bg-blue-900 transition-all">Simpan Disposisi</button>
        </div>
    </form>
</div>

<!-- ⚙️ SCRIPT LOGIKA ABU-ABU / DISABLED -->
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