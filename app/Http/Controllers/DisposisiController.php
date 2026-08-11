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
            'catatan_kadis'     => 'required|string|min:3',
            'diteruskan_kepada' => 'nullable|array',
        ], [
            'catatan_kadis.required' => 'Catatan disposisi wajib diisi! Tidak boleh dikosongkan.',
            'catatan_kadis.min'      => 'Catatan disposisi minimal 3 karakter.',
            'status_disposisi.required' => 'Pilih status disposisi terlebih dahulu.',
        ]);

        $diteruskanKepada = isset($validated['diteruskan_kepada']) 
            ? json_encode($validated['diteruskan_kepada']) 
            : null;

        Disposisi::updateOrCreate(
            ['agenda_id' => $agenda->id],
            [
                'status_disposisi'  => $validated['status_disposisi'],
                'catatan_kadis'     => $validated['catatan_kadis'],
                'diteruskan_kepada' => $diteruskanKepada,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Disposisi pimpinan berhasil disimpan.');
    }
}