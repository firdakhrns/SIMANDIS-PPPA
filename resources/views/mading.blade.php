@extends('layouts.app')

@section('content')

<!-- Header Bar -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Mading Jadwal & Monitoring Agenda</h2>
        <p class="text-xs text-slate-500 mt-1">Pantau seluruh agenda sosialisasi dan status disposisi pimpinan secara real-time.</p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Tombol Tambah Agenda (Khusus Admin) -->
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('agenda.create') }}" class="px-4 py-2.5 bg-navy text-white rounded-xl text-xs font-semibold hover:bg-blue-900 transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> + Registrasi Agenda
            </a>
        @endif

        <!-- Cetak PDF Rekap Bulanan -->
        <a href="{{ route('cetak.bulanan') }}" target="_blank" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-file-pdf text-red-500"></i> Ekspor PDF Mading
        </a>
    </div>
</div>

<!-- 📅 TIMELINE VIEW (AGENDA HARI INI) -->
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-8">
    <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-calendar-day text-navy"></i> Timeline Agenda
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($agendas->take(3) as $agenda)
            @php
                $borderColors = [
                    1 => 'border-l-pka',
                    2 => 'border-l-pp',
                    3 => 'border-l-pha',
                    4 => 'border-l-khp',
                ];
                $color = $borderColors[$agenda->bidang_id] ?? 'border-l-navy';
            @endphp
            <div class="bg-slate-50 p-4 rounded-2xl border-l-4 {{ $color }} border border-slate-100 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-navy bg-blue-100 px-2.5 py-1 rounded-lg">
                        {{ \Carbon\Carbon::parse($agenda->tgl_surat)->format('d M Y') }}
                    </span>
                    @if($agenda->status_disposisi === 'Disposisi')
                        <span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-md">
                            <i class="fa-solid fa-check-double"></i> Disposisi
                        </span>
                    @else
                        <span class="text-[10px] font-bold bg-blue-100 text-navy px-2 py-0.5 rounded-md">
                            Hadir
                        </span>
                    @endif
                </div>
                <h4 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $agenda->perihal }}</h4>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-building text-slate-400"></i> {{ $agenda->surat_dari }}
                </p>
            </div>
        @empty
            <p class="text-xs text-slate-400 col-span-3 text-center py-4">Belum ada agenda terdaftar hari ini.</p>
        @endforelse
    </div>
</div>

<!-- 📋 DAFTAR TABEL AGENDA LENGKAP -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h3 class="text-sm font-bold text-slate-800">Daftar Agenda Lengkap</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-400 font-semibold uppercase border-b border-slate-100">
                <tr>
                    <th class="p-4">No. Agenda</th>
                    <th class="p-4">Tanggal & Hal</th>
                    <th class="p-4">Pengirim</th>
                    <th class="p-4">Bidang</th>
                    <th class="p-4">Status Disposisi</th>
                    <th class="p-4 text-center">Aksi Operasional</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($agendas as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-navy">{{ $item->no_agenda }}</td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800 text-sm">{{ $item->perihal }}</p>
                            <span class="text-[11px] text-slate-400">No Surat: {{ $item->no_surat }}</span>
                        </td>
                        <td class="p-4 font-medium text-slate-700">{{ $item->surat_dari }}</td>
                        <td class="p-4">
                            @php
                                $bidangNames = [1 => 'PKA', 2 => 'PP', 3 => 'PHA', 4 => 'KHP'];
                                $badgeStyles = [
                                    1 => 'bg-purple-100 text-pka',
                                    2 => 'bg-pink-100 text-pp',
                                    3 => 'bg-emerald-100 text-pha',
                                    4 => 'bg-cyan-100 text-khp',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $badgeStyles[$item->bidang_id] ?? 'bg-slate-100 text-slate-600' }}">
                                Bidang {{ $bidangNames[$item->bidang_id] ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($item->status_disposisi === 'Disposisi')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                    Disposisi
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-navy">
                                    Hadir Kadis
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center space-x-1">
                            <!-- AKSI KHUSUS KADIS: ISI DISPOSISI -->
                            @if(Auth::user()->role === 'kadis')
                                <a href="{{ route('disposisi.edit', $item->id) }}" class="px-3 py-1.5 bg-navy text-white rounded-lg font-semibold hover:bg-blue-900 transition-all inline-block">
                                    <i class="fa-solid fa-pen-to-square"></i> Atur Disposisi
                                </a>
                            @endif

                            <!-- AKSI KHUSUS USER STAF: ISI REALISASI -->
                            @if(Auth::user()->role === 'user' && Auth::user()->bidang_id === $item->bidang_id)
                                <a href="{{ route('realisasi.create', $item->id) }}" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition-all inline-block">
                                    <i class="fa-solid fa-upload"></i> Isi Laporan
                                </a>
                            @endif

                            <!-- CETAK LEMBAR DISPOSISI PDF -->
                            <a href="{{ route('cetak.kegiatan', $item->id) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg font-semibold hover:bg-slate-200 transition-all inline-block" title="Print Disposisi PDF">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-8 text-slate-400">Tidak ada data agenda kegiatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection