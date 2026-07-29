@extends('layouts.app')

@section('content')

@php
    $bulan = $bulan ?? request('bulan', now()->month);
    $tahun = $tahun ?? request('tahun', now()->year);
    $agendas = $agendas ?? collect();

    // -------------------------------------------------------------------------
    // LOGIKA DINAMIS HITUNG STATISTIK & PERSENTASE
    // -------------------------------------------------------------------------
    $totalAgenda = $agendas->count();

    // 1. Hitung Total Disposisi & Persentase Ketercapaian Disposisi
    $totalDisposisi = $agendas->filter(fn($a) => !empty($a->status_disposisi))->count();
    $persenDisposisi = $totalAgenda > 0 ? round(($totalDisposisi / $totalAgenda) * 100) : 0;

    // 2. Hitung Total Kehadiran Kadis
    $totalKehadiranKadis = $agendas->filter(fn($a) => in_array($a->status_disposisi, ['Hadir', 'Hadir Langsung']))->count();

    // 3. Hitung Minggu Ke-berapa saat ini dalam bulan tersebut
    $namaBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->translatedFormat('F');
    $mingguKe = \Carbon\Carbon::now()->weekOfMonth;
@endphp

<div class="max-w-full overflow-hidden space-y-6">

    <!-- Header & Periode Laporan -->
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

            <a href="{{ route('cetak.kalender') }}" target="_blank" class="px-4 py-2 bg-navy text-white text-xs font-bold rounded-xl hover:bg-blue-900 transition-all shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- 📊 3 STAT CARDS (DINAMIS DARI DATABASE) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Card 1: Total Agenda -->
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

        <!-- Card 2: Total Disposisi Kadis -->
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

        <!-- Card 3: Kehadiran Kadis -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Kehadiran Kadis</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-2xl font-extrabold text-rose-600">{{ $totalKehadiranKadis }}</h3>
                    @if($totalKehadiranKadis > 0)
                        <span class="px-2 py-0.5 bg-rose-100 text-rose-600 font-extrabold text-[9px] rounded-full uppercase">Urgent Action</span>
                    @endif
                </div>
                <span class="text-[10px] font-bold text-slate-400 mt-1 block">Minggu ke-{{ $mingguKe }} {{ $namaBulan }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-bell font-bold"></i>
            </div>
        </div>
    </div>

    <!-- 📋 TABEL MONITORING STATUS AGENDA -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                @if(Auth::user()->role === 'admin')
                    <h3 class="text-sm font-bold text-slate-800">Monitoring Status Seluruh Agenda Instansi</h3>
                @else
                    <h3 class="text-sm font-bold text-slate-800">Daftar Agenda Menunggu Disposisi Kadis</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Prioritas peninjauan surat masuk dan undangan</p>
                @endif
            </div>

            @if(Auth::user()->role === 'kadis')
                <span class="px-3 py-1 bg-rose-100 text-rose-600 font-extrabold text-xs rounded-full">
                    ! {{ $agendas->filter(fn($a) => empty($a->status_disposisi))->count() }} Agenda Pending
                </span>
            @endif
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs table-fixed">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-3 w-12 text-center">NO</th>
                        <th class="p-3 w-32">TANGGAL & JAM</th>
                        <th class="p-3 w-40">SURAT DARI</th>
                        <th class="p-3">PERIHAL / AGENDA</th>
                        <th class="p-3 w-40 text-center">BIDANG PENANGGUNG JAWAB</th>
                        
                        @if(Auth::user()->role === 'admin')
                            <th class="p-3 w-36 text-center">STATUS DISPOSISI</th>
                        @else
                            <th class="p-3 w-28 text-center">AKSI</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agendas->take(3) as $index => $item)
                        @php
                            $bidangBadge = [
                                1 => ['nama' => 'PKA', 'bg' => 'bg-purple-100 text-purple-700'],
                                2 => ['nama' => 'PP',  'bg' => 'bg-pink-100 text-pink-700'],
                                3 => ['nama' => 'PHA', 'bg' => 'bg-emerald-100 text-emerald-700'],
                                4 => ['nama' => 'KHP', 'bg' => 'bg-cyan-100 text-cyan-700'],
                            ][$item->bidang_id] ?? ['nama' => 'UMUM', 'bg' => 'bg-slate-100 text-slate-700'];

                            $statusDisposisi = $item->status_disposisi;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-3 font-bold text-slate-400 text-center">{{ sprintf('%02d', $index + 1) }}</td>
                            <td class="p-3">
                                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->tgl_surat)->locale('id')->translatedFormat('d F Y') }}</p>
                                <span class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($item->tgl_surat)->format('H:i') }} WITA</span>
                            </td>
                            <td class="p-3 font-bold text-slate-700 truncate" title="{{ $item->surat_dari }}">
                                {{ $item->surat_dari }}
                            </td>
                            <td class="p-3 font-bold text-navy">
                                <p class="truncate max-w-md" title="{{ $item->perihal }}">
                                    {{ $item->perihal }}
                                </p>
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2.5 py-1 font-black text-[9px] rounded-full uppercase {{ $bidangBadge['bg'] }}">
                                    {{ $bidangBadge['nama'] }}
                                </span>
                            </td>

                            @if(Auth::user()->role === 'admin')
                                <td class="p-3 text-center">
                                    @if(!empty($statusDisposisi))
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 font-bold text-[10px] rounded-full inline-block">
                                            Sudah Disposisi
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 font-bold text-[10px] rounded-full inline-block">
                                            Menunggu Kadis
                                        </span>
                                    @endif
                                </td>
                            @else
                                <td class="p-3 text-center">
                                    <a href="{{ route('disposisi.edit', $item->id) }}" class="px-3 py-1.5 bg-navy text-white text-[10px] font-bold rounded-xl hover:bg-blue-900 transition-colors inline-block">
                                        Atur Disposisi
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-6 text-slate-400">Tidak ada agenda saat ini.</td>
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
                    <tr class="bg-slate-50 text-slate-400 font-extrabold uppercase text-[10px] tracking-wider text-center border-b border-slate-200">
                        <th class="p-2 border-r border-slate-200 text-rose-500">MINGGU</th>
                        <th class="p-2 border-r border-slate-200">SENIN</th>
                        <th class="p-2 border-r border-slate-200">SELASA</th>
                        <th class="p-2 border-r border-slate-200">RABU</th>
                        <th class="p-2 border-r border-slate-200">KAMIS</th>
                        <th class="p-2 border-r border-slate-200">JUMAT</th>
                        <th class="p-2">SABTU</th>
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
                                    <td class="p-1.5 border-r border-slate-200 bg-slate-50/40 h-20 vertical-top"></td>
                                @else
                                    @php
                                        $dateString = \Carbon\Carbon::create($tahun, $bulan, $currentDay)->format('Y-m-d');
                                        $eventsToday = $agendas->filter(function($item) use ($dateString) {
                                            return \Carbon\Carbon::parse($item->tgl_surat)->format('Y-m-d') === $dateString;
                                        });
                                        $isToday = \Carbon\Carbon::today()->format('Y-m-d') === $dateString;
                                        $isSunday = $col === 0;
                                    @endphp
                                    
                                    <td class="p-1.5 border-r border-slate-200 h-20 align-top transition-colors relative {{ $isToday ? 'bg-blue-50/50' : 'hover:bg-slate-50' }}">
                                        <span class="text-[11px] font-bold block mb-1 {{ $isSunday ? 'text-rose-500' : 'text-slate-700' }}">
                                            {{ $currentDay }}
                                        </span>

                                        <div class="space-y-1">
                                            @foreach($eventsToday as $event)
                                                @php
                                                    $badgeColor = (($event->status_disposisi ?? '') === 'Hadir' || ($event->status_disposisi ?? '') === 'Hadir Langsung')
                                                        ? 'bg-red-600' 
                                                        : ($bidangBgColor[$event->bidang_id] ?? 'bg-navy');
                                                @endphp

                                                <div class="{{ $badgeColor }} text-white text-[8px] font-bold px-1 py-0.5 rounded truncate cursor-pointer leading-tight"
                                                     title="{{ $event->perihal }} ({{ $event->surat_dari }})">
                                                    {{ $event->perihal }}
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

@endsection