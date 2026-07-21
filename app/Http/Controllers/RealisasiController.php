<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Realisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealisasiController extends Controller
{
    public function create($agenda_id)
    {
        $agenda = Agenda::findOrFail($agenda_id);

        if ($agenda->bidang_id !== Auth::user()->bidang_id) {
            abort(403, 'Akses ditolak! Anda tidak berwenang mengisi realisasi untuk bidang lain.');
        }

        return view('form-realisasi', compact('agenda'));
    }

    public function store(Request $request, $agenda_id)
    {
        $agenda = Agenda::findOrFail($agenda_id);

        if ($agenda->bidang_id !== Auth::user()->bidang_id) {
            abort(403, 'Akses ditolak! Anda tidak berwenang mengisi realisasi untuk bidang lain.');
        }

        $request->validate([
            'jumlah_peserta' => 'required|integer|min:0',
            'file_surat_tugas' => 'required|file|mimes:pdf|max:2048', 
            'foto_dokumentasi' => 'required|array|min:1',
            'foto_dokumentasi.*' => 'image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $fileSurat = $request->file('file_surat_tugas');
        $namaFileSurat = time() . '_surat_tugas.' . $fileSurat->getClientOriginalExtension();
        $fileSurat->move(public_path('uploads/surat_tugas'), $namaFileSurat);

        $pathFoto = [];
        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $index => $foto) {
                $namaFoto = time() . '_dok_' . $index . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $namaFoto);
                $pathFoto[] = $namaFoto;
            }
        }

        Realisasi::create([
            'agenda_id'        => $agenda->id,
            'jumlah_peserta' => $request->jumlah_peserta,
            'file_surat_tugas' => $namaFileSurat,
            'foto_dokumentasi' => $pathFoto, 
        ]);

        return redirect()->route('mading.index')->with('success', 'Laporan realisasi kegiatan berhasil disimpan.');
    }
}