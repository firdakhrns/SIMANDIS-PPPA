<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'user') {
            $agendas = Agenda::where('bidang_id', Auth::user()->bidang_id)
                             ->with('realisasi')
                             ->orderBy('tgl_surat', 'desc')
                             ->get();
        } else {
            $agendas = Agenda::with('realisasi')
                             ->orderBy('tgl_surat', 'desc')
                             ->get();
        }

        return view('mading', compact('agendas'));
    }

    public function create()
    {
        return view('form-undangan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string',
            'tgl_surat' => 'required|date',
            'tgl_diterima' => 'required|date',
            'no_agenda' => 'required|string',
            'sifat_surat' => 'required|in:Sangat Segera,Segera,Rahasia',
            'surat_dari' => 'required|string',
            'perihal' => 'required|string',
            'bidang_id' => 'required|integer|between:1,4', 
        ]);

        Agenda::create($validated);

        return redirect()->route('mading.index')->with('success', 'Surat undangan masuk berhasil diregistrasi.');
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete(); 

        return redirect()->route('mading.index')->with('success', 'Agenda kegiatan berhasil dihapus.');
    }
}