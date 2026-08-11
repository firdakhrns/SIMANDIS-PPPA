@extends('layouts.app')

@section('content')

@php
    $bulan = $bulan ?? request('bulan', now()->month);
    $tahun = $tahun ?? request('tahun', now()->year);
    $agendas = $agendas ?? collect();
    $agendaPending = $agendaPending ?? collect();
    $todayDate = $todayDate ?? \Carbon\Carbon::now()->format('Y-m-d');

    $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->translatedFormat('F');
@endphp

<div class="max-w-full overflow-hidden space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Executive Monitoring</h2>
            <p class="text-sm font-bold text-slate-700 mt-0.5">
                Periode Laporan: <span class="text-navy">{{ $namaBulan }} {{ $tahun }}</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <select name="bulan" onchange="this.form.submit()" class="bg-white border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 text-slate-700 shadow-xs focus:outline-none focus:border-navy">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <select name="tahun" onchange="this.form.submit()" class="bg-white border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 text-slate-700 shadow-xs focus:outline-none focus:border-navy">
                    @for($y = 2024; $y <= 2028; $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <!-- 📊 3 KARTU STATISTIK -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Agenda Sosialisasi</p>
                <h3 class="text-2xl font-extrabold text-navy">{{ $totalAgenda }}</h3>
                <span class="text-[10px] font-bold text-emerald-600 mt-1 block">Periode {{ $namaBulan }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-navy flex items-center justify-center text-lg">
                <i class="fa-solid fa-bullhorn font-bold"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Disposisi Kadis</p>
                <h3 class="text-2xl font-extrabold text-navy">{{ $totalDisposisi }}</h3>
                <span class="text-[10px] font-bold text-blue-600 mt-1 block">Tercapai {{ $persenDisposisi }}%</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-navy flex items-center justify-center text-lg">
                <i class="fa-solid fa-clipboard-check font-bold"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Kehadiran Kadis</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-2xl font-extrabold text-emerald-600">{{ $totalKehadiranKadis }}</h3>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 font-extrabold text-[9px] rounded-full uppercase">Terlaksana</span>
                </div>
                <span class="text-[10px] font-bold text-slate-400 mt-1 block">Agenda Kadis Hadir & Terlaksana</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-check font-bold"></i>
            </div>
        </div>
    </div>

    <!-- 📋 TABEL AGENDA MENUNGGU DISPOSISI KADIS -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Agenda Menunggu Disposisi Kadis</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar seluruh agenda yang belum diatur disposisinya pada bulan {{ $namaBulan }}</p>
            </div>

            <span class="px-3 py-1 bg-rose-100 text-rose-600 font-extrabold text-xs rounded-full">
                {{ $agendaPending->count() }} Agenda Pending 
            </span>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-fixed">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-3 w-12 text-center">NO</th>
                        <th class="p-3 w-32">TANGGAL & JAM</th>
                        <th class="p-3 w-40">SURAT DARI</th>
                        <th class="p-3">PERIHAL / AGENDA</th>
                        <th class="p-3 w-36 text-center">BIDANG PENANGGUNG JAWAB</th>
                        <th class="p-3 w-36 text-center">STATUS DISPOSISI</th>
                        <th class="p-3 w-36 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agendaPending as $index => $item)
                        @php
                            $bidangBadge = [
                                1 => ['nama' => 'PKA', 'bg' => 'bg-purple-100 text-purple-700'],
                                2 => ['nama' => 'PP',  'bg' => 'bg-pink-100 text-pink-700'],
                                3 => ['nama' => 'PHA', 'bg' => 'bg-emerald-100 text-emerald-700'],
                                4 => ['nama' => 'KHP', 'bg' => 'bg-cyan-100 text-cyan-700'],
                            ][$item->bidang_id] ?? ['nama' => 'UMUM', 'bg' => 'bg-slate-100 text-slate-700'];

                            $tglKegiatan = $item->tgl_kegiatan ?? ($item->surat->tgl_surat ?? $item->tgl_surat);
                            $tglFormat = \Carbon\Carbon::parse($tglKegiatan)->format('Y-m-d');
                            $isExpired = $tglFormat < $todayDate;
                            $perihalDisplay = $item->surat->perihal ?? $item->perihal;
                            $pengirimDisplay = $item->surat->surat_dari ?? $item->surat_dari;
                            $statusDisposisi = $item->disposisi->status_disposisi ?? null;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-3 font-bold text-slate-400 text-center">{{ sprintf('%02d', $loop->iteration) }}</td>
                            <td class="p-3">
                                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($tglKegiatan)->locale('id')->translatedFormat('d F Y') }}</p>
                                <span class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($tglKegiatan)->format('H:i') }} WITA</span>
                            </td>
                            <td class="p-3 font-bold text-slate-700 truncate" title="{{ $pengirimDisplay }}">
                                {{ $pengirimDisplay }}
                            </td>
                            <td class="p-3 font-bold text-navy">
                                <p class="truncate max-w-md" title="{{ $perihalDisplay }}">
                                    {{ $perihalDisplay }}
                                </p>
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2.5 py-1 font-black text-[9px] rounded-full uppercase {{ $bidangBadge['bg'] }}">
                                    {{ $bidangBadge['nama'] }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                @if($isExpired)
                                    <span class="px-3 py-1 bg-rose-50 text-rose-600 font-bold text-[10px] rounded-full inline-block border border-rose-100">
                                        Terlewat / Expired
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 font-bold text-[10px] rounded-full inline-block">
                                        Menunggu Kadis
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if(Auth::user()->role === 'kadis')
                                    @if($isExpired)
                                        <span class="px-3 py-1.5 bg-slate-100 text-slate-400 font-bold text-[10px] rounded-xl inline-block cursor-not-allowed" title="Agenda sudah lewat tanggal">
                                            <i class="fa-solid fa-lock text-[9px] mr-1"></i> Terkunci
                                        </span>
                                    @else
                                        <a href="{{ route('disposisi.edit', $item->id) }}" class="px-3 py-1.5 bg-navy text-white text-[10px] font-bold rounded-xl hover:bg-blue-900 transition-colors inline-block">
                                            Atur Disposisi
                                        </a>
                                    @endif
                                @elseif(Auth::user()->role === 'admin')
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('agenda.edit', $item->id) }}" class="text-slate-400 hover:text-navy text-xs" title="Edit Agenda">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('agenda.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus agenda ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 text-xs" title="Hapus Agenda">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-6 text-slate-400">Tidak ada agenda pending yang menunggu disposisi pada bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 📅 KALENDER AGENDA BULANAN -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800">
                    Kalender Agenda Bulanan - {{ $namaBulan }} {{ $tahun }}
                </h3>
                <div class="flex items-center gap-3 mt-1.5 text-[10px] font-bold text-slate-500">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-600 inline-block"></span> PKA</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-pink-500 inline-block"></span> PP</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> PHA</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-cyan-500 inline-block"></span> KHP</span>
                </div>
            </div>

            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl self-start sm:self-auto">
                <a href="{{ route('dashboard', ['bulan' => $bulan == 1 ? 12 : $bulan - 1, 'tahun' => $bulan == 1 ? $tahun - 1 : $tahun]) }}" class="w-6 h-6 flex items-center justify-center rounded-lg bg-white text-slate-600 hover:bg-slate-200 text-xs shadow-xs">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="px-2 text-xs font-bold text-slate-700">
                    {{ $namaBulan }} {{ $tahun }}
                </span>
                <a href="{{ route('dashboard', ['bulan' => $bulan == 12 ? 1 : $bulan + 1, 'tahun' => $bulan == 12 ? $tahun + 1 : $tahun]) }}" class="w-6 h-6 flex items-center justify-center rounded-lg bg-white text-slate-600 hover:bg-slate-200 text-xs shadow-xs">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full border-collapse border border-slate-200 text-left table-fixed min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 font-extrabold uppercase text-[11px] tracking-wider text-center border-b border-slate-200">
                        <th class="p-2.5 border-r border-slate-200 text-rose-500">MINGGU</th>
                        <th class="p-2.5 border-r border-slate-200">SENIN</th>
                        <th class="p-2.5 border-r border-slate-200">SELASA</th>
                        <th class="p-2.5 border-r border-slate-200">RABU</th>
                        <th class="p-2.5 border-r border-slate-200">KAMIS</th>
                        <th class="p-2.5 border-r border-slate-200">JUMAT</th>
                        <th class="p-2.5">SABTU</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $firstDayOfMonth = \Carbon\Carbon::create($tahun, $bulan, 1);
                        $daysInMonth = $firstDayOfMonth->daysInMonth;
                        $startDayOfWeek = $firstDayOfMonth->dayOfWeek;
                        $currentDay = 1;

                        $bidangBgColor = [
                            1 => 'bg-purple-600',
                            2 => 'bg-pink-500',
                            3 => 'bg-emerald-500',
                            4 => 'bg-cyan-500',
                        ];
                    @endphp

                    @for ($row = 0; $row < 6; $row++)
                        @if ($currentDay > $daysInMonth)
                            @break
                        @endif
                        <tr class="border-b border-slate-200">
                            @for ($col = 0; $col < 7; $col++)
                                @if (($row === 0 && $col < $startDayOfWeek) || $currentDay > $daysInMonth)
                                    <td class="p-2 border-r border-slate-200 bg-slate-50/40 h-24"></td>
                                @else
                                    @php
                                        $dateString = \Carbon\Carbon::create($tahun, $bulan, $currentDay)->format('Y-m-d');
                                        $eventsToday = $agendas->filter(function($item) use ($dateString) {
                                            $tgl = $item->tgl_kegiatan ?? ($item->surat->tgl_surat ?? $item->tgl_surat);
                                            return \Carbon\Carbon::parse($tgl)->format('Y-m-d') === $dateString;
                                        });
                                        $isToday = \Carbon\Carbon::today()->format('Y-m-d') === $dateString;
                                    @endphp

                                    <td onclick="showCalendarPopup('{{ \Carbon\Carbon::create($tahun, $bulan, $currentDay)->locale('id')->translatedFormat('d F Y') }}', {{ json_encode($eventsToday->values()) }})" 
                                        class="p-2 border-r border-slate-200 h-24 align-top transition-colors cursor-pointer relative hover:bg-blue-50/80 {{ $isToday ? 'bg-blue-100/50' : '' }}">
                                        
                                        <span class="text-sm font-black block mb-1.5 {{ $col === 0 ? 'text-rose-500' : 'text-slate-800' }}">
                                            {{ $currentDay }}
                                        </span>

                                        <div class="space-y-1">
                                            @foreach($eventsToday as $event)
                                                @php
                                                    $stDisposisi = $event->disposisi->status_disposisi ?? $event->status_disposisi;
                                                    $titleText = $event->surat->perihal ?? $event->perihal;
                                                    $badgeColor = ($stDisposisi === 'Hadir' || $stDisposisi === 'Hadir Langsung')
                                                        ? 'bg-red-600' 
                                                        : ($bidangBgColor[$event->bidang_id] ?? 'bg-navy');
                                                @endphp

                                                <div class="{{ $badgeColor }} text-white text-[10px] font-bold px-1.5 py-1 rounded-md truncate leading-tight shadow-2xs">
                                                    {{ $titleText }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    @php $currentDay++; @endphp
                                @endif
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="calendarDetailModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 id="modalCalendarTitle" class="text-xs font-black text-navy uppercase tracking-wider"></h3>
            <button onclick="closeCalendarPopup()" class="text-slate-400 hover:text-rose-600 text-xl font-bold">&times;</button>
        </div>

        <div id="modalCalendarContent" class="space-y-3 text-xs max-h-80 overflow-y-auto">
        </div>

        <div class="flex justify-end pt-2">
            <button onclick="closeCalendarPopup()" class="px-5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Tutup</button>
        </div>
    </div>
</div>

<script>
function showCalendarPopup(dateFormatted, events) {
    const modal = document.getElementById('calendarDetailModal');
    const title = document.getElementById('modalCalendarTitle');
    const content = document.getElementById('modalCalendarContent');

    title.innerText = "Agenda Kegiatan: " + dateFormatted;
    content.innerHTML = "";

    if (!events || events.length === 0) {
        content.innerHTML = `
            <div class="p-6 text-center text-slate-400 font-bold space-y-2">
                <i class="fa-regular fa-calendar-xmark text-4xl block text-slate-300"></i>
                <p class="text-xs">Belum ada agenda kegiatan</p>
            </div>
        `;
    } else {
        events.forEach((item, idx) => {
            const perihal = item.surat ? item.surat.perihal : (item.perihal || '-');
            const pengirim = item.surat ? item.surat.surat_dari : (item.surat_dari || '-');
            const noSurat = item.surat ? item.surat.no_surat : (item.no_surat || '-');
            const statusDisposisi = item.disposisi ? item.disposisi.status_disposisi : (item.status_disposisi || 'Menunggu Kadis');

            content.innerHTML += `
                <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl space-y-1.5">
                    <span class="px-2 py-0.5 bg-navy text-white font-extrabold text-[9px] rounded-md">Agenda #${idx + 1}</span>
                    <h4 class="font-bold text-slate-800 text-xs">${perihal}</h4>
                    <p class="text-slate-500 text-[11px]"><b>Pengirim:</b> ${pengirim}</p>
                    <p class="text-slate-500 text-[11px]"><b>No. Surat:</b> ${noSurat}</p>
                    <p class="text-slate-500 text-[11px]"><b>Disposisi Kadis:</b> <span class="text-navy font-bold">${statusDisposisi}</span></p>
                </div>
            `;
        });
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCalendarPopup() {
    const modal = document.getElementById('calendarDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection