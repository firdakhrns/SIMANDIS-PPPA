<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Agenda;
use App\Models\Disposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $bidangFilter = $request->query('bidang');
        $search = $request->query('search'); 
        $status = $request->query('status'); 
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $query = Agenda::with(['surat', 'disposisi'])->orderBy('tgl_kegiatan', 'desc');

        if ($request->routeIs('mading.bidang')) {
            if ($role === 'user') {
                $query->where('bidang_id', Auth::user()->bidang_id);
            } elseif ($bidangFilter) {
                $query->where('bidang_id', $bidangFilter);
            }
        } else {
            if ($bidangFilter) {
                $query->where('bidang_id', $bidangFilter);
            }
        }

        if ($search) {
            $query->where(function($mainQuery) use ($search) {
                $mainQuery->whereHas('surat', function($q) use ($search) {
                    $q->where('perihal', 'like', "%{$search}%")
                      ->orWhere('surat_dari', 'like', "%{$search}%")
                      ->orWhere('no_surat', 'like', "%{$search}%");
                })->orWhere('no_agenda', 'like', "%{$search}%");
            });
        }

        if ($status === 'terlaksana') {
            $query->where('status_pelaksanaan', 'terlaksana');
        } elseif ($status === 'belum') {
            $query->where('status_pelaksanaan', 'belum');
        }

        if ($bulan) {
            $query->whereHas('surat', function($q) use ($bulan) {
                $q->whereRaw("strftime('%m', tgl_surat) = ?", [sprintf('%02d', $bulan)]);
            });
        }

        if ($tahun) {
            $query->whereHas('surat', function($q) use ($tahun) {
                $q->whereRaw("strftime('%Y', tgl_surat) = ?", [(string)$tahun]);
            });
        }

        $agendas = $query->paginate(10)->withQueryString();

        if ($request->routeIs('mading.bidang')) {
            return view('mading-bidang', compact('agendas'));
        }

        return view('mading', compact('agendas'));
    }

    public function create()
    {
        return view('form-undangan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat'          => ['required', 'string', 'min:10', 'max:50', 'regex:/^[a-zA-Z0-9\s\/\.\-]+$/'],
            'no_agenda'         => 'required|string|max:50',
            'tgl_surat_date'    => 'required|date',
            'tgl_surat_time'    => 'required',
            'tgl_kegiatan_date' => 'required|date',
            'tgl_diterima'      => 'required|date',
            'surat_dari'        => ['required', 'string', 'max:255', 'regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\.\,\-\(\)\&\']+$/'],
            'sifat_surat'       => 'required|in:Segera,Sangat Segera,Rahasia',
            'perihal'           => ['required', 'string', 'min:10', 'max:100', 'regex:/^[a-zA-Z0-9\s\.\,\-\/\(\)\?\"\'\:\;]+$/'],
            'bidang_id'         => 'required|integer|in:1,2,3,4',
            'file_pdf'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::transaction(function () use ($request) {
            $fileName = null;
            if ($request->hasFile('file_pdf')) {
                $file = $request->file('file_pdf');
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $request->no_surat) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/undangan'), $fileName);
            }

            $surat = Surat::create([
                'no_surat'     => $request->no_surat,
                'tgl_surat'    => $request->tgl_surat_date . ' ' . $request->tgl_surat_time,
                'tgl_diterima' => $request->tgl_diterima,
                'sifat_surat'  => $request->sifat_surat,
                'surat_dari'   => $request->surat_dari,
                'perihal'      => $request->perihal,
                'file_pdf'     => $fileName,
            ]);

            $agenda = Agenda::create([
                'surat_id'     => $surat->id,
                'no_agenda'    => $request->no_agenda,
                'bidang_id'    => $request->bidang_id,
                'tgl_kegiatan' => $request->tgl_kegiatan_date,
                'jam_kegiatan' => $request->tgl_surat_time,
            ]);

            Disposisi::create([
                'agenda_id'        => $agenda->id,
                'status_disposisi' => null, 
                'catatan_kadis'    => null,
            ]);
        });

        $targetRoute = Auth::user()->role === 'admin' ? 'mading.index' : 'mading.bidang';
        return redirect()->route($targetRoute)->with('success', 'Agenda kegiatan berhasil diregistrasi.');
    }

    public function toggleStatus($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->status_pelaksanaan = ($agenda->status_pelaksanaan === 'terlaksana') ? 'belum' : 'terlaksana';
        $agenda->save();

        return back()->with('success', 'Status pelaksanaan berhasil diperbarui.');
    }

    public function edit($id)
    {
        $agenda = Agenda::with('surat')->findOrFail($id);
        return view('form-undangan', compact('agenda'));
    }

    public function destroy($id)
    {
        $agenda = Agenda::with('surat')->findOrFail($id);

        if ($agenda->surat && $agenda->surat->file_pdf) {
            $filePath = public_path('uploads/undangan/' . $agenda->surat->file_pdf);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $agenda->surat->delete();
        } else {
            $agenda->delete();
        }

        $targetRoute = Auth::user()->role === 'admin' ? 'mading.index' : 'mading.bidang';
        return redirect()->route($targetRoute)->with('success', 'Agenda kegiatan berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::with('surat')->findOrFail($id);

        $request->validate([
            'no_surat'          => ['required', 'string', 'min:10', 'max:50'],
            'no_agenda'         => 'required|string|max:50',
            'tgl_surat_date'    => 'required|date',
            'tgl_surat_time'    => 'required',
            'tgl_kegiatan_date' => 'required|date',
            'tgl_diterima'      => 'required|date',
            'surat_dari'        => 'required|string|max:255',
            'sifat_surat'       => 'required|in:Segera,Sangat Segera,Rahasia',
            'perihal'           => 'required|string|min:10|max:100',
            'bidang_id'         => 'required|integer',
            'file_pdf'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::transaction(function () use ($request, $agenda) {
            $fileName = $agenda->surat->file_pdf ?? null;
            if ($request->hasFile('file_pdf')) {
                $file = $request->file('file_pdf');
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $request->no_surat) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/undangan'), $fileName);
            }

            if ($agenda->surat) {
                $agenda->surat->update([
                    'no_surat'     => $request->no_surat,
                    'tgl_surat'    => $request->tgl_surat_date . ' ' . $request->tgl_surat_time,
                    'tgl_diterima' => $request->tgl_diterima,
                    'sifat_surat'  => $request->sifat_surat,
                    'surat_dari'   => $request->surat_dari,
                    'perihal'      => $request->perihal,
                    'file_pdf'     => $fileName,
                ]);
            }

            $agenda->update([
                'no_agenda'    => $request->no_agenda,
                'bidang_id'    => $request->bidang_id,
                'tgl_kegiatan' => $request->tgl_kegiatan_date,
                'jam_kegiatan' => $request->tgl_surat_time,
            ]);
        });

        $targetRoute = Auth::user()->role === 'admin' ? 'mading.index' : 'mading.bidang';
        return redirect()->route($targetRoute)->with('success', 'Data agenda kegiatan berhasil diperbarui.');
    }
}