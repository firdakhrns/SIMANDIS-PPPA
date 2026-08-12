@extends('layouts.app')

@section('content')

<div class="max-w-full overflow-hidden space-y-6">

    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-xs font-bold text-navy uppercase tracking-wider px-2">Arsip Surat Undangan Masuk</h2>

        <form method="GET" action="{{ route('surat.index') }}" class="flex items-center gap-3">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. surat / pengirim / perihal..." 
                       class="bg-slate-50/80 border border-slate-200 text-xs rounded-xl pl-9 pr-4 py-2.5 w-72 focus:outline-none focus:border-navy text-slate-700 placeholder:text-slate-400">
            </div>

            <div class="relative flex items-center">
                <input type="date" name="tgl_surat" value="{{ request('tgl_surat') }}" onchange="this.form.submit()" 
                       class="bg-slate-50/80 border border-slate-200 text-xs font-bold rounded-xl pl-9 pr-4 py-2.5 text-slate-700 focus:outline-none focus:border-navy cursor-pointer">
                <i class="fa-regular fa-calendar absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
            </div>

            @if(request('search') || request('tgl_surat'))
                <a href="{{ route('surat.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition-colors" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-2xl flex items-center justify-between">
            <span><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-5">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-navy flex items-center justify-center text-xl">
                <i class="fa-solid fa-bullhorn font-bold"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">TOTAL SURAT MASUK BULAN INI</p>
                <h3 class="text-3xl font-black text-navy">{{ $totalBulanIni }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-5">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-navy flex items-center justify-center text-xl">
                <i class="fa-solid fa-clipboard-check font-bold"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">TOTAL SELURUH SURAT MASUK</p>
                <h3 class="text-3xl font-black text-navy">{{ $totalSeluruh }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-2">
            <span class="w-1 h-4 bg-navy rounded-full"></span>
            <h3 class="text-xs font-bold text-slate-800">Daftar Surat Undangan Masuk</h3>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left text-xs border-separate border-spacing-y-2">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-3 w-10 text-center">NO</th>
                        <th class="p-3">NOMOR SURAT & PERIHAL</th>
                        <th class="p-3 w-1 whitespace-nowrap">TGL SURAT</th>
                        <th class="p-3 w-1 whitespace-nowrap">TGL KEGIATAN</th>
                        <th class="p-3 w-1 whitespace-nowrap pr-12">SURAT DARI</th>
                        <th class="p-3 w-1 whitespace-nowrap text-center">FILE SURAT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surats as $index => $item)
                        @php
                            $tglKegiatan = $item->agenda->tgl_kegiatan ?? null;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors border-b border-slate-50">
                            <td class="p-3 font-bold text-slate-400 text-center">
                                {{ sprintf('%02d', (method_exists($surats, 'firstItem') ? $surats->firstItem() + $index : $index + 1)) }}
                            </td>

                            <td class="p-3 pr-6">
                                <div class="flex flex-col gap-0.5">
                                    <span class="font-bold text-navy leading-tight">{{ $item->no_surat }}</span>
                                    <span class="text-[11px] text-slate-500 font-medium leading-tight truncate max-w-sm" title="{{ $item->perihal }}">
                                        {{ $item->perihal }}
                                    </span>
                                </div>
                            </td>

                            <td class="p-3 text-slate-700 font-semibold whitespace-nowrap pr-8">
                                {{ \Carbon\Carbon::parse($item->tgl_surat)->locale('id')->translatedFormat('d F Y') }}
                            </td>

                            <td class="p-3 font-bold text-slate-800 whitespace-nowrap pr-8">
                                @if($tglKegiatan)
                                    {{ \Carbon\Carbon::parse($tglKegiatan)->locale('id')->translatedFormat('d F Y') }}
                                @else
                                    <span class="text-slate-400 italic font-normal">-</span>
                                @endif
                            </td>

                            <td class="p-3 font-bold text-slate-700 whitespace-nowrap pr-12">
                                {{ $item->surat_dari }}
                            </td>

                            <td class="p-3 text-center whitespace-nowrap">
                                @if($item->file_pdf)
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ asset('uploads/undangan/' . $item->file_pdf) }}" target="_blank" class="px-2.5 py-1 bg-blue-50 text-navy font-bold text-[10px] rounded-lg hover:bg-blue-100 transition-colors inline-flex items-center gap-1" title="Lihat PDF">
                                            <i class="fa-solid fa-file-pdf"></i> Lihat
                                        </a>
                                        <a href="{{ asset('uploads/undangan/' . $item->file_pdf) }}" download class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px] rounded-lg hover:bg-emerald-100 transition-colors inline-flex items-center gap-1" title="Unduh File">
                                            <i class="fa-solid fa-download"></i> Unduh
                                        </a>
                                    </div>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-400 font-bold text-[10px] rounded-lg">Tanpa File</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-8 text-slate-400">Belum ada dokumen surat undangan dalam arsip.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex items-center justify-between">
            @if(request('search') || request('tgl_surat'))
                <a href="{{ route('surat.index') }}" class="text-xs font-bold text-navy hover:underline inline-flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter (Lihat Semua {{ $totalSeluruh }} Surat)
                </a>
            @else
                <p class="text-xs text-slate-400">Menampilkan seluruh {{ $totalSeluruh }} surat masuk</p>
            @endif

            @if(method_exists($surats, 'hasPages') && $surats->hasPages())
                <div>
                    {{ $surats->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

@endsection