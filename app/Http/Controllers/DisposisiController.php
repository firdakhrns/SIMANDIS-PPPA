<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Disposisi;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function edit($id)
    {
        $agenda = Agenda::with(['surat', 'disposisi'])->findOrFail($id);

        if ($agenda->status_pelaksanaan === 'terlaksana') {
            return redirect()->back()->with('error', 'Kegiatan ini sudah terlaksana. Disposisi tidak dapat diubah lagi.');
        }

        return view('form-disposisi', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        if ($agenda->status_pelaksanaan === 'terlaksana') {
            return redirect()->back()->with('error', 'Kegiatan ini sudah terlaksana.');
        }

        $validated = $request->validate([
            'status_disposisi'  => 'required|in:Hadir,Disposisi',
            'catatan_kadis'     => 'required_if:status_disposisi,Disposisi|nullable|string|min:3',
            'diteruskan_kepada' => 'nullable|array',
        ], [
            'status_disposisi.required' => 'Pilih status disposisi terlebih dahulu.',
            'catatan_kadis.required_if' => 'Catatan disposisi wajib diisi jika status memilih Disposisi!',
            'catatan_kadis.min'         => 'Catatan disposisi minimal 3 karakter.',
        ]);

        $isHadir = $validated['status_disposisi'] === 'Hadir';
        
        $catatanKadis = $isHadir ? null : ($validated['catatan_kadis'] ?? null);
        $diteruskanKepada = ($isHadir || !isset($validated['diteruskan_kepada'])) 
            ? null 
            : json_encode($validated['diteruskan_kepada']);

        Disposisi::updateOrCreate(
            ['agenda_id' => $agenda->id],
            [
                'status_disposisi'  => $validated['status_disposisi'],
                'catatan_kadis'     => $catatanKadis,
                'diteruskan_kepada' => $diteruskanKepada,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Disposisi pimpinan berhasil disimpan.');
    }

    public function cetak($id)
    {
        $agenda = Agenda::with(['surat', 'disposisi'])->findOrFail($id);

        if (!$agenda->disposisi || empty($agenda->disposisi->status_disposisi)) {
            return redirect()->back()->with('error', 'Lembar disposisi belum diatur oleh Kepala Dinas.');
        }

        return view('cetak.lembar-disposisi', compact('agenda'));
    }
}