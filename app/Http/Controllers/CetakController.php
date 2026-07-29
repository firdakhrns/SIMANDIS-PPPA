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

    public function pdfKalender(Request $request)
    {
        $bulan = $request->query('bulan', date('m'));
        $tahun = $request->query('tahun', date('Y'));

        // Ambil data agenda bulan & tahun terkait, lalu dikelompokkan berdasarkan tanggal (1-31)
        $agendas = Agenda::with('realisasi')
            ->whereMonth('tgl_surat', $bulan)
            ->whereYear('tgl_surat', $tahun)
            ->get()
            ->groupBy(function($val) {
                return \Carbon\Carbon::parse($val->tgl_surat)->format('j'); // Group angka tanggal 1, 2, 3...
            });

        // Set kertas Landscape A4 agar grid kalender muat dan rapi
        $pdf = Pdf::loadView('cetak.kalender-monitoring', compact('agendas', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Rekap_Kalender_Monitoring_' . $bulan . '_' . $tahun . '.pdf');
    }
}