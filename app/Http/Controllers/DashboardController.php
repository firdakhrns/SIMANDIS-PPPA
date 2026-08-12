<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);
        $todayDate = now()->format('Y-m-d');

        $agendas = Agenda::with(['surat', 'disposisi'])
            ->whereHas('surat', function($q) use ($bulan, $tahun) {
                $q->whereMonth('tgl_surat', $bulan)
                  ->whereYear('tgl_surat', $tahun);
            })
            ->get();

        $totalAgenda = $agendas->count();

        $totalDisposisi = $agendas->filter(function($item) {
            $st = $item->disposisi->status_disposisi ?? null;
            return !is_null($st) && trim($st) !== '' && in_array(trim($st), ['Hadir', 'Disposisi', 'Hadir Langsung']);
        })->count();

        $persenDisposisi = $totalAgenda > 0 ? round(($totalDisposisi / $totalAgenda) * 100) : 0;

        $totalKehadiranKadis = $agendas->filter(function($item) {
            $stDisposisi = $item->disposisi->status_disposisi ?? null;
            $stPelaksanaan = $item->status_pelaksanaan;
            return !is_null($stDisposisi) && in_array(trim($stDisposisi), ['Hadir', 'Hadir Langsung']) && $stPelaksanaan === 'terlaksana';
        })->count();

        $agendaPending = $agendas->filter(function($item) {
            $stDisposisi = $item->disposisi->status_disposisi ?? null;
            return is_null($stDisposisi) || trim($stDisposisi) === '';
        });

        return view('dashboard-kalender', compact(
            'agendas', 
            'agendaPending',
            'totalAgenda', 
            'totalDisposisi', 
            'persenDisposisi', 
            'totalKehadiranKadis', 
            'bulan', 
            'tahun',
            'todayDate'
        ));
    }
}