<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    public function pdfKegiatan($id)
    {
        $agenda = Agenda::findOrFail($id);

        if (Auth::user()->role === 'user' && $agenda->bidang_id !== Auth::user()->bidang_id) {
            abort(403, 'Akses ditolak.');
        }

        $pdf = Pdf::loadView('cetak.lembar-disposisi', compact('agenda'));
        
        return $pdf->stream('Lembar_Disposisi_' . $agenda->no_agenda . '.pdf');
    }

    public function pdfBulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        if (Auth::user()->role === 'user') {
            $agendas = Agenda::where('bidang_id', Auth::user()->bidang_id)
                             ->whereMonth('tgl_surat', $bulan)
                             ->whereYear('tgl_surat', $tahun)
                             ->with('realisasi')
                             ->get();
        } else {
            $agendas = Agenda::whereMonth('tgl_surat', $bulan)
                             ->whereYear('tgl_surat', $tahun)
                             ->with('realisasi')
                             ->get();
        }

        $pdf = Pdf::loadView('cetak.rekap-bulanan', compact('agendas', 'bulan', 'tahun'));
        return $pdf->stream('Rekap_Agenda_' . $bulan . '_' . $tahun . '.pdf');
    }
}