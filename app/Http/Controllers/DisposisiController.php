<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        'catatan_kadis'      => 'nullable|string',
        'diteruskan_kepada'  => 'nullable|array',
    ]);

    if (isset($validated['diteruskan_kepada'])) {
        $validated['diteruskan_kepada'] = json_encode($validated['diteruskan_kepada']);
    }

    $agenda->update($validated);

    return redirect()->route('mading.index')->with('success', 'Status disposisi pimpinan berhasil diperbarui.');
}
public function cetak($id)
    {
        $agenda = Agenda::findOrFail($id);
        
        $pdf = Pdf::loadView('cetak.lembar-disposisi', compact('agenda')); 
        
        return $pdf->stream('Lembar-Disposisi-' . $agenda->id . '.pdf');
    }
}