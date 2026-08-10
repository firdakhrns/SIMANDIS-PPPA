<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('surat_dari', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tgl_surat')) {
            $tgl = $request->tgl_surat;
            $query->where('tgl_surat', 'like', "{$tgl}%");
        }

        $surats = $query->latest('tgl_surat')->paginate(10);

        $totalBulanIni = Agenda::whereMonth('tgl_surat', now()->month)->count();
        $totalSeluruh = Agenda::count();

        return view('arsip-surat', compact('surats', 'totalBulanIni', 'totalSeluruh'));
    }

    private function getFilePath($fileName)
    {
        if (!$fileName) return null;

        $paths = [
            public_path('uploads/undangan/' . $fileName),
            storage_path('app/public/surat/' . $fileName),
            storage_path('app/public/' . $fileName),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    public function preview($id)
    {
        $agenda = Agenda::findOrFail($id);

        $fileName = $agenda->file_pdf ?? $agenda->file_surat;

        $filePath = $this->getFilePath($fileName);

        if (!$filePath) {
            abort(404, "File '{$fileName}' tidak ditemukan di folder public/uploads/undangan/.");
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    public function download($id)
    {
        $agenda = Agenda::findOrFail($id);

        $fileName = $agenda->file_pdf ?? $agenda->file_surat;

        $filePath = $this->getFilePath($fileName);

        if (!$filePath) {
            return redirect()->back()->with('error', "File '{$fileName}' tidak ditemukan di server.");
        }

        return response()->download($filePath, $fileName);
    }
}