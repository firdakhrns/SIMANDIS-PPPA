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
            <table class="w-full text-left text-xs table-fixed">
                <thead>
                    <tr class="bg-slate-50/70 text-slate-400 font-extrabold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="p-4 w-16 text-center">NO</th>
                        <th class="p-4 w-64">NO. SURAT & AGENDA</th>
                        <th class="p-4 w-36">TANGGAL MASUK</th>
                        <th class="p-4 w-52">PENGIRIM / SURAT DARI</th>
                        <th class="p-4">FILE BERKAS</th>
                        <th class="p-4 w-32 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surats as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-bold text-slate-400 text-center">
                                {{ sprintf('%02d', $surats->firstItem() + $index) }}
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-navy hover:underline cursor-pointer truncate" title="{{ $item->no_surat }}">
                                    {{ $item->no_surat }}
                                </p>
                                <span class="text-[10px] text-slate-400 block truncate" title="{{ $item->perihal }}">{{ $item->perihal }}</span>
                            </td>
                            <td class="p-4 font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($item->tgl_surat)->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td class="p-4 font-bold text-slate-700 truncate" title="{{ $item->surat_dari }}">
                                {{ $item->surat_dari }}
                            </td>
                            
                            <td class="p-4">
                                @if(!empty($item->file_pdf) || !empty($item->file_surat))
                                    @php $fileName = $item->file_pdf ?? $item->file_surat; @endphp
                                    <div class="inline-flex items-center gap-2 px-2.5 py-1.5 bg-rose-50 rounded-lg text-rose-600 font-bold text-xs max-w-full">
                                        <i class="fa-solid fa-file-pdf text-sm shrink-0"></i>
                                        <span class="truncate text-slate-700 font-medium text-[11px]" title="{{ $fileName }}">
                                            {{ $fileName }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-slate-300 font-bold px-2">-</span>
                                @endif
                            </td>

                            <!-- AKSI PREVIEW & DOWNLOAD -->
                            <td class="p-4 text-center">
                                @if(!empty($item->file_pdf) || !empty($item->file_surat))
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('surat.preview', $item->id) }}" target="_blank" title="Lihat Surat" 
                                           class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-navy hover:text-white text-slate-500 flex items-center justify-center transition-all">
                                            <i class="fa-regular fa-eye text-xs"></i>
                                        </a>

                                        <a href="{{ route('surat.download', $item->id) }}" title="Unduh Berkas" 
                                           class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-500 flex items-center justify-center transition-all">
                                            <i class="fa-solid fa-download text-xs"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">Tanpa File</span>
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

        <div class="p-4 text-center border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
            <a href="{{ route('surat.index') }}" class="text-xs font-bold text-navy hover:underline inline-flex items-center gap-2">
                <i class="fa-solid fa-list-check"></i> Lihat Semua Surat ({{ $totalSeluruh }})
            </a>
        </div>
    </div>

</div>

@endsection