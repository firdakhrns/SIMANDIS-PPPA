<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                ->orWhere('surat_dari', 'like', "%{$search}%")
                ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tgl_surat')) {
            $tgl = $request->tgl_surat;
            $query->where('tgl_surat', 'like', "{$tgl}%");
        }

        $surats = $query->latest('tgl_surat')->paginate(10);

        $totalBulanIni = Agenda::whereMonth('tgl_surat', now()->month)->count();
        $totalSeluruh = Agenda::count();

        return view('arsip-surat', compact('surats', 'totalBulanIni', 'totalSeluruh'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat'     => 'required|string|max:100',
            'surat_dari'   => 'required|string|max:255',
            'perihal'      => 'required|string',
            'tgl_surat'    => 'required|date',
            'tgl_diterima' => 'nullable|date', 
            'bidang_id'    => 'required|integer',
            'file_surat'   => 'required|file|mimes:pdf|max:5120',
        ]);

        $validated['tgl_diterima'] = $request->filled('tgl_diterima') 
            ? $request->tgl_diterima 
            : now()->format('Y-m-d');

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $request->no_surat) . '.pdf';
            
            $file->storeAs('public/surat', $fileName);
            $validated['file_surat'] = $fileName;
        }

        Agenda::create($validated);

        return redirect()->back()->with('success', 'Berkas surat & agenda berhasil disimpan.');
    }

    public function preview($id)
    {
        $agenda = Agenda::findOrFail($id);

        if (!$agenda->file_surat || !Storage::exists('public/surat/' . $agenda->file_surat)) {
            abort(404, 'File surat tidak ditemukan di storage server.');
        }

        $path = storage_path('app/public/surat/' . $agenda->file_surat);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $agenda->file_surat . '"'
        ]);
    }

    public function download($id)
    {
        $agenda = Agenda::findOrFail($id);

        if (!$agenda->file_surat || !Storage::exists('public/surat/' . $agenda->file_surat)) {
            return redirect()->back()->with('error', 'File surat tidak ditemukan.');
        }

        $path = storage_path('app/public/surat/' . $agenda->file_surat);

        return response()->download($path, $agenda->file_surat);
    }
}