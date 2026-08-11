<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('surat_dari', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tgl_surat')) {
            $query->whereDate('tgl_surat', $request->tgl_surat);
        }

        $surats = $query->latest('tgl_surat')->paginate(10);

        $totalBulanIni = Surat::whereMonth('tgl_surat', now()->month)->count();
        $totalSeluruh = Surat::count();

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
        $surat = Surat::findOrFail($id);
        $filePath = $this->getFilePath($surat->file_pdf);

        if (!$filePath) {
            abort(404, "File PDF tidak ditemukan di server.");
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $surat->file_pdf . '"'
        ]);
    }

    public function download($id)
    {
        $surat = Surat::findOrFail($id);
        $filePath = $this->getFilePath($surat->file_pdf);

        if (!$filePath) {
            return redirect()->back()->with('error', "File PDF tidak ditemukan di server.");
        }

        return response()->download($filePath, $surat->file_pdf);
    }
}