<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Realisasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $agendas = Agenda::with('realisasi')
            ->whereMonth('tgl_surat', $bulan)
            ->whereYear('tgl_surat', $tahun)
            ->orderBy('tgl_surat', 'asc')
            ->get();

        return view('dashboard-kalender', compact('agendas', 'bulan', 'tahun'));
    }

    public function getEvents()
    {
        $agendas = Agenda::with('realisasi')->get();
        $events = [];

        $colorMap = [
            1 => ['bg' => '#8B5CF6', 'name' => 'BIDANG PKA', 'badge' => 'bg-purple-100 text-purple-700'],
            2 => ['bg' => '#EC4899', 'name' => 'BIDANG PP',  'badge' => 'bg-pink-100 text-pink-700'],
            3 => ['bg' => '#10B981', 'name' => 'BIDANG PHA', 'badge' => 'bg-emerald-100 text-emerald-700'],
            4 => ['bg' => '#06B6D4', 'name' => 'BIDANG KHP', 'badge' => 'bg-cyan-100 text-cyan-700'],
        ];

        foreach ($agendas as $agenda) {
            $bidangData = $colorMap[$agenda->bidang_id] ?? ['bg' => '#1E3A8A', 'name' => 'UMUM', 'badge' => 'bg-slate-100 text-slate-700'];

            $events[] = [
                'id'    => $agenda->id,
                'title' => $agenda->perihal,
                'start' => \Carbon\Carbon::parse($agenda->tgl_surat)->toIso8601String(),
                'backgroundColor' => $bidangData['bg'],
                'borderColor'     => $bidangData['bg'],
                'extendedProps'   => [
                    'surat_dari'       => $agenda->surat_dari,
                    'status_disposisi' => $agenda->status_disposisi ?? 'Belum Disposisi',
                    'status_realisasi' => $agenda->realisasi ? 'Terlaksana' : 'Belum Terlaksana',
                    'nama_bidang'      => $bidangData['name'],
                    'badge_color'      => $bidangData['badge'],
                ]
            ];
        }

        return response()->json($events);
    }
}