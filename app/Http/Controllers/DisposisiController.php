<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return view('form-disposisi', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $validated = $request->validate([
            'status_disposisi'   => 'required|in:Hadir,Disposisi', 
            'diteruskan_kepada'  => 'nullable|array', 
            'instruksi_pimpinan' => 'nullable|array', 
            'catatan_kadis'      => 'nullable|string',
        ]);

        $agenda->update($validated);

        return redirect()->route('mading.index')->with('success', 'Lembar disposisi berhasil diperbarui oleh Kepala Dinas.');
    }
}