@extends('layouts.app')

@section('content')

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Mading Utama</h2>

    <div class="flex flex-wrap items-center gap-3">
        <select onchange="location = this.value;" class="bg-white border border-slate-200 text-xs font-semibold rounded-xl px-3.5 py-2 text-slate-700 shadow-xs focus:outline-none focus:border-navy">
            <option value="{{ route('mading.index', request()->except('bulan')) }}">Filter Bulan</option>
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ route('mading.index', array_merge(request()->query(), ['bulan' => $m])) }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F') }}
                </option>
            @endfor
        </select>

        <select onchange="location = this.value;" class="bg-white border border-slate-200 text-xs font-semibold rounded-xl px-3.5 py-2 text-slate-700 shadow-xs focus:outline-none focus:border-navy">
            <option value="{{ route('mading.index', request()->except('bidang')) }}">Filter Bidang</option>
            <option value="{{ route('mading.index', array_merge(request()->query(), ['bidang' => 1])) }}" {{ request('bidang') == 1 ? 'selected' : '' }}>Bidang PKA</option>
            <option value="{{ route('mading.index', array_merge(request()->query(), ['bidang' => 2])) }}" {{ request('bidang') == 2 ? 'selected' : '' }}>Bidang PP</option>
            <option value="{{ route('mading.index', array_merge(request()->query(), ['bidang' => 3])) }}" {{ request('bidang') == 3 ? 'selected' : '' }}>Bidang PHA</option>
            <option value="{{ route('mading.index', array_merge(request()->query(), ['bidang' => 4])) }}" {{ request('bidang') == 4 ? 'selected' : '' }}>Bidang KHP</option>
        </select>

        <select onchange="location = this.value;" class="bg-white border border-slate-200 text-xs font-semibold rounded-xl px-3.5 py-2 text-slate-700 shadow-xs focus:outline-none focus:border-navy">
            <option value="{{ route('mading.index', request()->except('status')) }}">Status Terlaksana</option>
            <option value="{{ route('mading.index', array_merge(request()->query(), ['status' => 'terlaksana'])) }}" {{ request('status') === 'terlaksana' ? 'selected' : '' }}>Terlaksana</option>
            <option value="{{ route('mading.index', array_merge(request()->query(), ['status' => 'belum'])) }}" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Terlaksana</option>
        </select>

        @if(Auth::user()->role === 'admin')
            <a href="{{ route('agenda.create') }}" class="px-4 py-2 bg-navy text-white text-xs font-bold rounded-xl hover:bg-blue-900 transition-all shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Jadwal Kegiatan
            </a>
        @endif

        @if(request('bidang') || request('status') || request('search') || request('bulan'))
            <a href="{{ route('mading.index') }}" class="px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5" title="Reset Filter">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
        @endif

        <form action="{{ route('mading.index') }}" method="GET" class="relative flex items-center">
            @foreach(request()->except(['search', 'page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach

            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari agenda..." 
                class="pl-9 pr-8 py-2 bg-slate-100/80 border-none rounded-xl text-xs text-slate-700 focus:ring-2 focus:ring-navy w-44 md:w-56"
            >
            @if(request('search'))
                <a href="{{ route('mading.index', request()->except('search')) }}" class="absolute right-2.5 text-slate-400 hover:text-slate-600 text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>
</div>

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-regular fa-calendar text-navy"></i> Agenda Hari Ini (Timeline View)
        </h3>
        <span class="text-xs font-medium text-slate-400">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
    </div>

    <div class="space-y-3">
        @php
            $todayAgendas = $agendas->filter(function($item) {
                $tgl = $item->tgl_kegiatan ?? ($item->surat->tgl_surat ?? $item->tgl_surat);
                return \Carbon\Carbon::parse($tgl)->isToday();
            });

            $borderColors = [1 => 'border-l-purple-500', 2 => 'border-l-pink-500', 3 => 'border-l-emerald-500', 4 => 'border-l-cyan-500'];
            $bidangBadge = [
                1 => ['nama' => 'BIDANG PKA', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                2 => ['nama' => 'BIDANG PP',  'bg' => 'bg-pink-100',   'text' => 'text-pink-700'],
                3 => ['nama' => 'BIDANG PHA', 'bg' => 'bg-emerald-100','text' => 'text-emerald-700'],
                4 => ['nama' => 'BIDANG KHP', 'bg' => 'bg-cyan-100',   'text' => 'text-cyan-700'],
            ];
        @endphp

        @forelse($todayAgendas as $agenda)
            @php
                $color = $borderColors[$agenda->bidang_id] ?? 'border-l-navy';
                $badge = $bidangBadge[$agenda->bidang_id] ?? ['nama' => 'UMUM', 'bg' => 'bg-slate-100', 'text' => 'text-slate-600'];
                $statusDisposisi = $agenda->disposisi->status_disposisi ?? $agenda->status_disposisi;
                $jam = $agenda->jam_kegiatan ?? '08:30';
                $tglDisplay = \Carbon\Carbon::parse(($agenda->tgl_kegiatan ?? date('Y-m-d')) . ' ' . $jam);
                $perihalDisplay = $agenda->surat->perihal ?? $agenda->perihal;
                $pengirimDisplay = $agenda->surat->surat_dari ?? $agenda->surat_dari;
                $lokasiDisplay = $agenda->lokasi ?? $pengirimDisplay;
            @endphp
            <div class="bg-white p-4 rounded-2xl border-l-4 {{ $color }} shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-5">
                    <div class="text-center min-w-[65px]">
                        <span class="text-base font-extrabold text-navy block leading-tight">
                            {{ \Carbon\Carbon::parse($tglDisplay)->format('H:i') }}
                        </span>
                        <span class="text-[9px] font-bold text-slate-400">WITA</span>
                    </div>

                    <div>
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded {{ $badge['bg'] }} {{ $badge['text'] }} mb-1 inline-block">
                            {{ $badge['nama'] }}
                        </span>
                        <h4 class="font-bold text-slate-800 text-sm">{{ $perihalDisplay }}</h4>
                        <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                            <i class="fa-solid fa-location-dot text-emerald-600"></i> {{ $lokasiDisplay }}
                        </p>
                    </div>
                </div>

                <div>
                    @if($statusDisposisi === 'Hadir')
                        <span class="px-3 py-1 bg-blue-50 text-navy font-bold text-xs rounded-full flex items-center gap-1.5 border border-blue-100">
                            <i class="fa-solid fa-user text-[10px]"></i> Kadis Hadir
                        </span>
                    @elseif($statusDisposisi === 'Disposisi')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 font-bold text-xs rounded-full flex items-center gap-1.5 border border-emerald-100">
                            <i class="fa-solid fa-share text-[10px]"></i> Disposisi
                        </span>
                    @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-400 font-bold text-xs rounded-full flex items-center gap-1.5 border border-slate-200">
                            <i class="fa-regular fa-clock text-[10px]"></i> Belum Diatur
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white p-6 rounded-2xl text-center text-xs text-slate-400 shadow-xs border border-slate-100">
                <i class="fa-regular fa-calendar-xmark text-xl mb-1 block text-slate-300"></i>
                Tidak ada agenda kegiatan terdaftar untuk hari ini.
            </div>
        @endforelse
    </div>
</div>

<div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
    <h3 class="text-sm font-bold text-slate-800 mb-4">Daftar Agenda Lengkap</h3>

    <div class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider p-3 rounded-xl flex items-center gap-4 mb-3">
        <div class="w-8 text-center shrink-0">NO</div>
        <div class="w-28 shrink-0">TANGGAL & JAM</div>
        <div class="flex-1 min-w-0">NAMA AGENDA & LOKASI</div>
        <div class="w-16 text-center shrink-0">BIDANG</div>
        <div class="w-32 text-center shrink-0">KEHADIRAN KADIS</div>
        <div class="w-28 text-center shrink-0">STATUS</div>
        <div class="w-32 text-center shrink-0">AKSI</div>
    </div>

    <div class="space-y-2 text-xs">
        @forelse($agendas as $index => $item)
            @php
                $bidangNames = [1 => 'PKA', 2 => 'PP', 3 => 'PHA', 4 => 'KHP'];
                $bidangColors = [
                    1 => 'bg-purple-100 text-purple-700',
                    2 => 'bg-pink-100 text-pink-700',
                    3 => 'bg-emerald-100 text-emerald-700',
                    4 => 'bg-cyan-100 text-cyan-700',
                ];
                $isTerlaksana = ($item->status_pelaksanaan === 'terlaksana');
                $statusDisposisi = $item->disposisi->status_disposisi ?? $item->status_disposisi ?? null;
                $hasDisposisi = !empty($statusDisposisi) && trim($statusDisposisi) === 'Disposisi';

                $jam = $item->jam_kegiatan ?? '08:30';
                $tglDisplay = \Carbon\Carbon::parse(($item->tgl_kegiatan ?? date('Y-m-d')) . ' ' . $jam);
                $perihalDisplay = $item->surat->perihal ?? $item->perihal;
                $pengirimDisplay = $item->surat->surat_dari ?? $item->surat_dari;
                $lokasiDisplay = $item->lokasi ?? $pengirimDisplay;

                $tglFormat = \Carbon\Carbon::parse($tglDisplay)->format('Y-m-d');
                $todayFormat = \Carbon\Carbon::now()->format('Y-m-d');
                $isExpired = ($tglFormat < $todayFormat) && !$hasDisposisi;
            @endphp

            <div class="p-3 bg-white hover:bg-slate-50/80 rounded-xl border border-slate-100 transition-colors flex items-center gap-4">
                <div class="w-8 font-bold text-slate-400 text-center shrink-0">
                    {{ sprintf('%02d', (method_exists($agendas, 'firstItem') ? $agendas->firstItem() + $index : $index + 1)) }}
                </div>

                <div class="w-28 shrink-0">
                    <p class="font-bold text-slate-800 leading-tight">{{ \Carbon\Carbon::parse($tglDisplay)->locale('id')->translatedFormat('d M Y') }}</p>
                    <span class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($tglDisplay)->format('H:i') }} WITA</span>
                </div>

                <div class="flex-1 min-w-0 pr-2">
                    <p class="font-bold text-navy hover:underline cursor-pointer leading-snug truncate" data-item="{{ json_encode($item->load('surat')) }}" onclick="handleDetailClick(this)">
                        {{ $perihalDisplay }}
                    </p>
                    <span class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5 truncate">
                        <i class="fa-solid fa-location-dot text-emerald-600"></i> {{ $lokasiDisplay }}
                    </span>
                </div>

                <div class="w-16 text-center shrink-0">
                    <span class="px-2 py-0.5 font-extrabold text-[9px] rounded uppercase {{ $bidangColors[$item->bidang_id] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $bidangNames[$item->bidang_id] ?? 'SEKRETARIAT' }}
                    </span>
                </div>

                <div class="w-32 text-center shrink-0">
                    @if($statusDisposisi === 'Hadir')
                        <span class="px-2.5 py-1 bg-blue-50 text-navy font-bold text-[10px] rounded-full inline-block border border-blue-100">
                            Hadir
                        </span>
                    @elseif($statusDisposisi === 'Disposisi')
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-bold text-[10px] rounded-full inline-block border border-emerald-100">
                            Disposisi
                        </span>
                    @elseif($isTerlaksana || $isExpired)
                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 font-bold text-[10px] rounded-full inline-block border border-rose-100">
                            Terlewat / Expired
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 font-bold text-[10px] rounded-full inline-block">
                            Menunggu Kadis
                        </span>
                    @endif
                </div>

                <div class="w-28 text-center shrink-0">
                    @if($isTerlaksana)
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 font-bold text-[10px] rounded-full inline-block">
                            ✓ Terlaksana
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-500 font-bold text-[10px] rounded-full inline-block">
                            Belum Terlaksana
                        </span>
                    @endif
                </div>

                <div class="w-32 text-center shrink-0 flex items-center justify-center gap-1.5">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('agenda.edit', $item->id) }}" class="p-1 text-slate-400 hover:text-navy transition-colors" title="Edit Agenda">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>

                        <button type="button" onclick="confirmDelete('{{ route('agenda.destroy', $item->id) }}')" class="p-1 text-slate-400 hover:text-rose-600 transition-colors" title="Hapus Agenda">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>

                        @if($hasDisposisi)
                            <a href="{{ route('disposisi.cetak', $item->id) }}" target="_blank" class="px-2 py-0.5 bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 text-[10px] font-bold rounded-lg transition-colors flex items-center gap-1" title="Cetak PDF">
                                <i class="fa-solid fa-file-pdf"></i> PDF
                            </a>
                        @endif

                    @elseif(Auth::user()->role === 'kadis')
                        @if($isTerlaksana || $isExpired)
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-400 font-bold text-[10px] rounded-lg cursor-not-allowed inline-block" title="Terkunci">
                                <i class="fa-solid fa-lock text-[9px] mr-1"></i> Terkunci
                            </span>
                        @elseif($hasDisposisi)
                            <a href="{{ route('disposisi.edit', $item->id) }}" class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded-lg hover:bg-emerald-100 transition-colors inline-block" title="Ubah Disposisi">
                                <i class="fa-solid fa-check text-[9px]"></i> Disposisi
                            </a>
                            <a href="{{ route('disposisi.cetak', $item->id) }}" target="_blank" class="p-1 text-rose-600 hover:text-rose-800 transition-colors" title="Cetak PDF">
                                <i class="fa-solid fa-file-pdf text-xs"></i>
                            </a>
                        @else
                            <a href="{{ route('disposisi.edit', $item->id) }}" class="px-2.5 py-1 bg-navy text-white text-[10px] font-bold rounded-lg hover:bg-blue-900 transition-colors inline-block">
                                Atur Disposisi
                            </a>
                        @endif
                    @endif

                    <button type="button" 
                            data-item="{{ json_encode($item->load('surat')) }}" 
                            onclick="handleDetailClick(this)"
                            class="px-2.5 py-1 bg-slate-100 text-slate-600 hover:bg-slate-200 text-[10px] font-bold rounded-lg transition-colors inline-block">
                        Detail
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center p-8 text-slate-400">Tidak ada data agenda kegiatan yang sesuai.</div>
        @endforelse
    </div>

    @if(method_exists($agendas, 'hasPages') && $agendas->hasPages())
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                Menampilkan {{ $agendas->firstItem() ?? 0 }} - {{ $agendas->lastItem() ?? 0 }} dari {{ $agendas->total() }} agenda
            </p>
            <div>
                {{ $agendas->links() }}
            </div>
        </div>
    @endif
</div>

<div id="detailModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl relative">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
            <h3 class="font-bold text-navy text-base">Detail Rincian Agenda Kegiatan</h3>
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
                    <p id="modalNoAgenda" class="font-bold text-navy"></p>
                </div>
            </div>

            <div>
                <span class="text-slate-400 block font-semibold">Perihal / Nama Agenda:</span>
                <p id="modalPerihal" class="font-bold text-navy text-sm"></p>
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

<div id="deleteModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl text-center space-y-4">
        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-800">Hapus Agenda Kegiatan?</h3>
            <p class="text-xs text-slate-400 mt-1">Data yang dihapus tidak dapat dikembalikan lagi.</p>
        </div>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-rose-600 text-white font-bold text-xs rounded-xl hover:bg-rose-700 shadow-xs">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
                <a href="/uploads/undangan/${filePdf}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-navy font-bold rounded-xl border border-blue-100 hover:bg-blue-100">
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

    function confirmDelete(url) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>

@endsection