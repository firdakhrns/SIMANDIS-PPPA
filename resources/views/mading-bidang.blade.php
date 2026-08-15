@extends('layouts.app')

@section('content')

@php
    $bidangTitle = [
        1 => 'Bidang Perlindungan Khusus Anak (PKA)',
        2 => 'Bidang Perlindungan Perempuan (PP)',
        3 => 'Bidang Pemenuhan Hak Anak (PHA)',
        4 => 'Bidang Kualitas Hidup Perempuan (KHP)',
    ][Auth::user()->bidang_id ?? request('bidang')] ?? 'Manajemen Agenda Bidang';
@endphp

<div class="mb-6">
    <h2 class="text-2xl font-bold text-[#1a2b4c]">Manajemen Agenda</h2>
    <p class="text-xs text-slate-400 mt-0.5">Kelola jadwal internal dan input agenda kegiatan {{ $bidangTitle }}.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Bulan Ini</p>
            <h3 class="text-2xl font-extrabold text-[#1a2b4c]">{{ $agendas->total() }} Kegiatan</h3>
            <span class="text-[10px] font-bold text-slate-400 mt-1 block">
                {{ $agendas->filter(fn($a) => $a->status_pelaksanaan === 'terlaksana')->count() }} Telah Terlaksana
            </span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1a2b4c] flex items-center justify-center text-lg">
            <i class="fa-regular fa-calendar font-bold"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mendatang</p>
            <h3 class="text-2xl font-extrabold text-[#1a2b4c]">
                {{ $agendas->filter(fn($a) => $a->status_pelaksanaan !== 'terlaksana')->count() }} Agenda
            </h3>
            <span class="text-[10px] font-bold text-slate-400 mt-1 block">Prioritas Segera</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1a2b4c] flex items-center justify-center text-lg">
            <i class="fa-regular fa-clock font-bold"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-slate-800">Daftar Kegiatan {{ $bidangTitle }}</h3>
        
        <form method="GET" action="{{ route('mading.bidang') }}" class="flex items-center gap-2">
            <select name="bulan" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-1.5 text-slate-700 focus:outline-none focus:border-[#1a2b4c]">
                <option value="">-- Semua Bulan --</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            @if(request('bulan'))
                <a href="{{ route('mading.bidang') }}" class="px-2.5 py-1.5 bg-rose-50 text-rose-600 text-xs font-bold rounded-xl hover:bg-rose-100">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-separate border-spacing-y-2">
            <thead>
                <tr class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                    <th class="p-3 rounded-l-xl">NO</th>
                    <th class="p-3">NO. SURAT & AGENDA</th>
                    <th class="p-3">PERIHAL & LOKASI</th>
                    <th class="p-3">TANGGAL & JAM</th>
                    <th class="p-3 text-center">STATUS PELAKSANAAN</th>
                    <th class="p-3 text-center rounded-r-xl">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agendas as $index => $item)
                    @php
                        $isTerlaksana = ($item->status_pelaksanaan === 'terlaksana');
                        $noSurat = $item->surat->no_surat ?? $item->no_surat ?? '-';
                        $perihal = $item->surat->perihal ?? $item->perihal ?? '-';
                        $pengirim = $item->surat->surat_dari ?? $item->surat_dari ?? '-';
                        $lokasi = $item->lokasi ?? $pengirim;
                        $tglTampil = $item->tgl_kegiatan ?? ($item->surat->tgl_surat ?? $item->tgl_surat);
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors border-b border-slate-50">
                        <td class="p-3 font-bold text-slate-400">{{ sprintf('%02d', $loop->iteration + (($agendas->currentPage() - 1) * $agendas->perPage())) }}</td>
                        <td class="p-3">
                            <p class="font-bold text-slate-800">{{ $noSurat }}</p>
                            <span class="text-[10px] text-navy font-bold">{{ $item->no_agenda ?? 'AGD-' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="p-3">
                            <p class="font-bold text-[#1a2b4c]">{{ $perihal }}</p>
                            <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-emerald-600"></i> {{ $lokasi }}
                            </span>
                        </td>
                        <td class="p-3">
                            <p class="font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($tglTampil)->locale('id')->translatedFormat('d M Y') }}
                            </p>
                            <span class="text-[10px] text-slate-400">
                                {{ $item->jam_kegiatan ?? \Carbon\Carbon::parse($tglTampil)->format('H:i') }} WITA
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            @if($isTerlaksana)
                                <span class="px-3 py-1 rounded-full font-bold text-[10px] inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 cursor-default select-none shadow-2xs" title="Agenda telah selesai diselenggarakan">
                                    Terlaksana <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                </span>
                            @else
                                <form action="{{ route('agenda.toggle-status', $item->id) }}" method="POST" id="status-form-{{ $item->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" 
                                            data-id="{{ $item->id }}"
                                            data-status="belum"
                                            onclick="handleStatusClick(this)"
                                            class="px-3 py-1 rounded-full font-bold text-[10px] inline-flex items-center gap-1 bg-slate-100 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 border border-transparent transition-all active:scale-95 shadow-2xs"
                                            title="Klik untuk menyelesaikan agenda">
                                        Belum <i class="fa-regular fa-circle-check text-slate-400"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-3">
                                @if(!$isTerlaksana)
                                    <a href="{{ route('agenda.edit', $item->id) }}" class="text-slate-400 hover:text-[#1a2b4c] text-xs" title="Edit Agenda">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                @endif

                                <form action="{{ route('agenda.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="handleDeleteClick({{ $item->id }})" class="text-slate-400 hover:text-red-500 text-xs" title="Hapus Agenda">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>

                                <button type="button" 
                                        data-item="{{ json_encode($item->load('surat')) }}" 
                                        onclick="handleDetailClick(this)" 
                                        class="text-xs font-bold text-[#1a2b4c] hover:underline">
                                    Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-8 text-slate-400">Belum ada agenda kegiatan untuk bidang ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $agendas->links() }}
    </div>
</div>

@if ($errors->any())
    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-2xl text-rose-700 text-xs shadow-xs">
        <div class="flex items-center gap-2 mb-1.5 font-bold text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
            <span>Gagal Menyimpan Agenda Kegiatan!</span>
        </div>
        <ul class="list-disc pl-5 space-y-1 font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-[#1a2b4c]"></i> Input Agenda Kegiatan Bidang
        </h3>
        <span class="px-3 py-1 bg-purple-100 text-purple-700 font-black text-[10px] uppercase rounded-full">
            {{ $bidangTitle }}
        </span>
    </div>

    <form method="POST" action="{{ route('agenda.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs" id="agendaForm">
        @csrf

        @if(Auth::user()->role === 'admin')
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Bidang Dituju <span class="text-rose-500">*</span></label>
                <select name="bidang_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#1a2b4c]">
                    <option value="">-- Pilih Bidang --</option>
                    <option value="1" {{ (Auth::user()->bidang_id ?? request('bidang')) == 1 ? 'selected' : '' }}>Bidang Perlindungan Khusus Anak (PKA)</option>
                    <option value="2" {{ (Auth::user()->bidang_id ?? request('bidang')) == 2 ? 'selected' : '' }}>Bidang Perlindungan Perempuan (PP)</option>
                    <option value="3" {{ (Auth::user()->bidang_id ?? request('bidang')) == 3 ? 'selected' : '' }}>Bidang Pemenuhan Hak Anak (PHA)</option>
                    <option value="4" {{ (Auth::user()->bidang_id ?? request('bidang')) == 4 ? 'selected' : '' }}>Bidang Kualitas Hidup Perempuan (KHP)</option>
                </select>
            </div>
        @else
            <input type="hidden" name="bidang_id" value="{{ Auth::user()->bidang_id ?? request('bidang', 1) }}">
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nomor Surat <span class="text-rose-500">*</span></label>
                <input type="text" name="no_surat" value="{{ old('no_surat') }}" minlength="3" maxlength="50" required placeholder="Contoh: 001/PPPA/PKA/2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">No Agenda <span class="text-rose-500">*</span></label>
                <input type="text" name="no_agenda" value="{{ old('no_agenda', 'AGD-' . time()) }}" required 
                    placeholder="Masukkan No Agenda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_surat_date" value="{{ old('tgl_surat_date') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Diterima <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_diterima" value="{{ old('tgl_diterima', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Pelaksanaan Kegiatan <span class="text-rose-500">*</span></label>
                <input type="date" name="tgl_kegiatan_date" value="{{ old('tgl_kegiatan_date') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jam Kegiatan <span class="text-rose-500">*</span></label>
                <input type="time" name="tgl_surat_time" value="{{ old('tgl_surat_time', '08:30') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Surat Dari / Pengirim <span class="text-rose-500">*</span></label>
                <input type="text" name="surat_dari" value="{{ old('surat_dari') }}" required placeholder="misal: ULM Banjarmasin" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Tempat / Lokasi Kegiatan (Opsional)</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="misal: Aula Lantai 3 DPPPA Kota Banjarmasin" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Sifat Surat <span class="text-rose-500">*</span></label>
            <select name="sifat_surat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#1a2b4c]">
                <option value="Segera" {{ old('sifat_surat') == 'Segera' ? 'selected' : '' }}>Segera</option>
                <option value="Sangat Segera" {{ old('sifat_surat') == 'Sangat Segera' ? 'selected' : '' }}>Sangat Segera</option>
                <option value="Rahasia" {{ old('sifat_surat') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
            </select>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Perihal / Nama Agenda <span class="text-rose-500">*</span></label>
            <textarea name="perihal" rows="3" minlength="5" maxlength="150" required placeholder="Masukkan perihal atau nama agenda kegiatan secara jelas..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#1a2b4c]">{{ old('perihal') }}</textarea>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">File Surat Undangan (PDF / Word) <span class="text-rose-500">*</span></label>
            <div id="dropZone" class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center bg-slate-50/50 hover:bg-slate-50 transition-colors cursor-pointer">
                <input type="file" name="file_pdf" id="fileInput" accept=".pdf,.doc,.docx" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <div id="filePlaceholder">
                    <i class="fa-solid fa-file-pdf text-3xl text-[#1a2b4c] mb-2"></i>
                    <p class="font-bold text-slate-700 text-xs">Klik atau tarik file ke sini untuk mengunggah</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">.pdf, .doc, .docx (Maksimal file size 5MB)</p>
                </div>
                <div id="filePreview" class="hidden">
                    <div class="flex items-center justify-center gap-3 p-3 bg-white rounded-xl border border-slate-200 max-w-sm mx-auto">
                        <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                        <div class="text-left flex-1 min-w-0">
                            <p id="fileName" class="font-bold text-slate-700 text-xs truncate"></p>
                            <p id="fileSize" class="text-[10px] text-slate-400"></p>
                        </div>
                        <button type="button" id="removeFile" class="text-slate-400 hover:text-red-500 p-1">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold hover:bg-slate-50">Batal</button>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#1a2b4c] text-white font-bold shadow-md hover:bg-blue-900 transition-colors">Simpan Agenda</button>
        </div>
    </form>
</div>

<div id="detailModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl relative">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
            <h3 class="font-bold text-[#1a2b4c] text-base">Detail Rincian Agenda Kegiatan</h3>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
        </div>
        
        <div class="mt-4 space-y-3 text-xs">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="text-slate-400 block font-semibold">Nomor Surat:</span>
                    <p id="modalNoSurat" class="font-bold text-slate-800"></p>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Nomor Agenda:</span>
                    <p id="modalNoAgenda" class="font-bold text-[#1a2b4c]"></p>
                </div>
            </div>

            <div>
                <span class="text-slate-400 block font-semibold">Perihal / Nama Agenda:</span>
                <p id="modalPerihal" class="font-bold text-[#1a2b4c] text-sm"></p>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="text-slate-400 block font-semibold">Pengirim / Instansi:</span>
                    <p id="modalPengirim" class="font-bold text-slate-800"></p>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Sifat Surat:</span>
                    <p id="modalSifat" class="font-bold text-slate-800"></p>
                </div>
            </div>

            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl space-y-2">
                <div>
                    <span class="text-slate-400 block font-semibold">Waktu Pelaksanaan:</span>
                    <p id="modalWaktu" class="font-bold text-slate-800"></p>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Tempat / Lokasi Kegiatan:</span>
                    <p id="modalLokasi" class="font-bold text-emerald-700"></p>
                </div>
            </div>

            <div id="modalFileContainer" class="pt-2"></div>
        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="closeDetailModal()" class="px-5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Tutup</button>
        </div>
    </div>
</div>

<div id="customDeleteModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl text-center space-y-4">
        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-800">Hapus Agenda Kegiatan?</h3>
            <p class="text-xs text-slate-400 mt-1">Data agenda ini akan dihapus secara permanen.</p>
        </div>
        <div class="flex items-center gap-3 pt-2">
            <button type="button" onclick="closeCustomDeleteModal()" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200">
                Batal
            </button>
            <button type="button" id="confirmDeleteBtn" class="flex-1 py-2.5 bg-rose-600 text-white font-bold text-xs rounded-xl hover:bg-rose-700 shadow-xs">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<div id="customStatusModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl text-center space-y-4">
        <div class="w-12 h-12 bg-blue-50 text-[#1a2b4c] rounded-2xl flex items-center justify-center mx-auto text-xl">
            <i class="fa-solid fa-circle-question"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-800">Ubah Status Agenda?</h3>
            <p id="statusModalText" class="text-xs text-slate-500 mt-1"></p>
        </div>
        <div class="flex items-center gap-3 pt-2">
            <button type="button" onclick="closeCustomStatusModal()" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200">
                Batal
            </button>
            <button type="button" id="confirmStatusBtn" class="flex-1 py-2.5 bg-[#1a2b4c] text-white font-bold text-xs rounded-xl hover:bg-blue-900 shadow-xs">
                Ya, Ubah!
            </button>
        </div>
    </div>
</div>

<script>
    let activeDeleteId = null;
    let activeStatusFormId = null;

    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const filePlaceholder = document.getElementById('filePlaceholder');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const removeFile = document.getElementById('removeFile');

        dropZone.addEventListener('click', function(e) {
            if (!e.target.closest('#removeFile')) {
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                validateAndHandleFile(this.files[0]);
            }
        });

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-[#1a2b4c]', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-[#1a2b4c]', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-[#1a2b4c]', 'bg-blue-50');
            const files = e.dataTransfer.files;
            if (files && files[0]) {
                validateAndHandleFile(files[0]);
            }
        });

        function validateAndHandleFile(file) {
            const allowedExt = ['pdf', 'doc', 'docx'];
            const ext = file.name.split('.').pop().toLowerCase();
            
            if (!allowedExt.includes(ext)) {
                alert('Format file tidak didukung! Hanya file .pdf, .doc, dan .docx yang diperbolehkan.');
                fileInput.value = '';
                return false;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5MB.');
                fileInput.value = '';
                return false;
            }

            handleFile(file);
            return true;
        }

        function handleFile(file) {
            filePlaceholder.classList.add('hidden');
            filePreview.classList.remove('hidden');
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
            
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }

        removeFile.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.value = '';
            filePreview.classList.add('hidden');
            filePlaceholder.classList.remove('hidden');
            dropZone.classList.remove('border-[#1a2b4c]', 'bg-blue-50');
        });
    });

    function handleStatusClick(button) {
        const id = button.getAttribute('data-id');
        const currentStatus = button.getAttribute('data-status');
        const targetStatus = currentStatus === 'terlaksana' ? 'Belum Terlaksana' : 'Terlaksana';

        activeStatusFormId = `status-form-${id}`;
        document.getElementById('statusModalText').innerText = `Apakah Anda yakin ingin mengubah status agenda ini menjadi "${targetStatus}"?`;
        
        document.getElementById('customStatusModal').classList.remove('hidden');
        document.getElementById('customStatusModal').classList.add('flex');
    }

    document.getElementById('confirmStatusBtn').addEventListener('click', function() {
        if (activeStatusFormId) {
            document.getElementById(activeStatusFormId).submit();
        }
    });

    function closeCustomStatusModal() {
        document.getElementById('customStatusModal').classList.add('hidden');
        document.getElementById('customStatusModal').classList.remove('flex');
    }

    function handleDeleteClick(id) {
        activeDeleteId = id;
        document.getElementById('customDeleteModal').classList.remove('hidden');
        document.getElementById('customDeleteModal').classList.add('flex');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (activeDeleteId) {
            document.getElementById(`delete-form-${activeDeleteId}`).submit();
        }
    });

    function closeCustomDeleteModal() {
        document.getElementById('customDeleteModal').classList.add('hidden');
        document.getElementById('customDeleteModal').classList.remove('flex');
    }

    function handleDetailClick(button) {
        const data = JSON.parse(button.getAttribute('data-item'));
        const noSurat = data.surat ? data.surat.no_surat : (data.no_surat || '-');
        const perihal = data.surat ? data.surat.perihal : (data.perihal || '-');
        const pengirim = data.surat ? data.surat.surat_dari : (data.surat_dari || '-');
        const sifat = data.surat ? data.surat.sifat_surat : (data.sifat_surat || '-');
        const filePdf = data.surat ? data.surat.file_pdf : (data.file_pdf || null);
        const tgl = data.tgl_kegiatan ?? (data.surat ? data.surat.tgl_surat : data.tgl_surat);
        const jam = data.jam_kegiatan ?? '08:30';
        const lokasi = data.lokasi ? data.lokasi : 'Lokasi disesuaikan instansi pengirim (' + pengirim + ')';

        document.getElementById('modalNoSurat').innerText = noSurat;
        document.getElementById('modalNoAgenda').innerText = data.no_agenda || '-';
        document.getElementById('modalPerihal').innerText = perihal;
        document.getElementById('modalPengirim').innerText = pengirim;
        document.getElementById('modalSifat').innerText = sifat;
        document.getElementById('modalWaktu').innerText = `${tgl} | Pukul ${jam} WITA`;
        document.getElementById('modalLokasi').innerText = lokasi;
        
        const fileContainer = document.getElementById('modalFileContainer');
        if (filePdf) {
            fileContainer.innerHTML = `
                <a href="/uploads/undangan/${filePdf}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-[#1a2b4c] font-bold rounded-xl border border-blue-100 hover:bg-blue-100">
                    <i class="fa-solid fa-file-pdf"></i> Lihat File Undangan PDF
                </a>`;
        } else {
            fileContainer.innerHTML = `<span class="text-slate-400 italic">Tidak ada lampiran file PDF.</span>`;
        }

        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailModal').classList.remove('flex');
    }
</script>

@endsection