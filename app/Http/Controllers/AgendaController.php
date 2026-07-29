<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $query = Agenda::with('realisasi')->orderBy('tgl_surat', 'desc');

        // Jika halaman Mading Bidang (route 'mading.bidang') & role user, filter bidangnya
        if ($request->routeIs('mading.bidang')) {
            if ($role === 'user') {
                $query->where('bidang_id', Auth::user()->bidang_id);
            } elseif ($bidangFilter) {
                $query->where('bidang_id', $bidangFilter);
            }
        } else {
            // Mading Utama: Bisa difilter lewat dropdown di atas jika diisi
            if ($bidangFilter) {
                $query->where('bidang_id', $bidangFilter);
            }
        }

        // Pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('perihal', 'like', "%{$search}%")
                  ->orWhere('surat_dari', 'like', "%{$search}%")
                  ->orWhere('no_surat', 'like', "%{$search}%")
                  ->orWhere('no_agenda', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($status === 'terlaksana') {
            $query->where('status_pelaksanaan', 'terlaksana')->orWhereHas('realisasi');
        } elseif ($status === 'belum') {
            $query->where('status_pelaksanaan', 'belum')->whereDoesntHave('realisasi');
        }

        if ($bulan) { $query->whereMonth('tgl_surat', $bulan); }
        if ($tahun) { $query->whereYear('tgl_surat', $tahun); }

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
        $validated = $request->validate([
            'no_surat'       => 'required|string',
            'tgl_surat_date' => 'required|date',
            'tgl_surat_time' => 'nullable',
            'tgl_diterima'   => 'required|date',
            'no_agenda'      => 'required|string',
            'sifat_surat'    => 'required|in:Sangat Segera,Segera,Rahasia',
            'surat_dari'     => 'required|string',
            'perihal'        => 'required|string',
            'bidang_id'      => 'required|integer|between:1,4',
            'file_pdf'       => 'nullable|file|mimes:pdf,doc,docx|max:50120',
        ]);

        $jam = $request->tgl_surat_time ?? '08:00';
        $fullDateTime = $request->tgl_surat_date . ' ' . $jam . ':00';

        $namaFilePdf = null;
        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $namaFilePdf = time() . '_undangan.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/undangan'), $namaFilePdf);
        }

        Agenda::create([
            'no_surat'           => $validated['no_surat'],
            'tgl_surat'          => $fullDateTime,
            'tgl_diterima'       => $validated['tgl_diterima'],
            'no_agenda'          => $validated['no_agenda'],
            'sifat_surat'        => $validated['sifat_surat'],
            'surat_dari'         => $validated['surat_dari'],
            'perihal'            => $validated['perihal'],
            'bidang_id'          => $validated['bidang_id'],
            'file_pdf'           => $namaFilePdf,
            'status_pelaksanaan' => 'belum',
        ]);

        $targetRoute = Auth::user()->role === 'admin' ? 'mading.index' : 'mading.bidang';

        return redirect()->route($targetRoute)->with('success', 'Agenda kegiatan berhasil ditambahkan.');
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
        $agenda = Agenda::findOrFail($id);
        return view('form-undangan', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $validated = $request->validate([
            'no_surat'       => 'required|string',
            'tgl_surat_date' => 'required|date',
            'tgl_surat_time' => 'nullable',
            'tgl_diterima'   => 'required|date',
            'no_agenda'      => 'required|string',
            'sifat_surat'    => 'required|in:Sangat Segera,Segera,Rahasia',
            'surat_dari'     => 'required|string',
            'perihal'        => 'required|string',
            'bidang_id'      => 'required|integer|between:1,4',
            'file_pdf'       => 'nullable|file|mimes:pdf,doc,docx|max:50120',
        ]);

        $jam = $request->tgl_surat_time ?? \Carbon\Carbon::parse($agenda->tgl_surat)->format('H:i');
        $fullDateTime = $request->tgl_surat_date . ' ' . $jam . ':00';

        if ($request->hasFile('file_pdf')) {
            if ($agenda->file_pdf && file_exists(public_path('uploads/undangan/' . $agenda->file_pdf))) {
                unlink(public_path('uploads/undangan/' . $agenda->file_pdf));
            }

            $file = $request->file('file_pdf');
            $namaFilePdf = time() . '_undangan.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/undangan'), $namaFilePdf);
            $agenda->file_pdf = $namaFilePdf;
        }

        $agenda->update([
            'no_surat'     => $validated['no_surat'],
            'tgl_surat'    => $fullDateTime,
            'tgl_diterima' => $validated['tgl_diterima'],
            'no_agenda'    => $validated['no_agenda'],
            'sifat_surat'  => $validated['sifat_surat'],
            'surat_dari'   => $validated['surat_dari'],
            'perihal'      => $validated['perihal'],
            'bidang_id'    => $validated['bidang_id'],
            'file_pdf'     => $agenda->file_pdf,
        ]);

        $targetRoute = Auth::user()->role === 'admin' ? 'mading.index' : 'mading.bidang';

        return redirect()->route($targetRoute)->with('success', 'Agenda kegiatan berhasil ditambahkan.');
    }

    public function destroy($id)
{
    $agenda = Agenda::findOrFail($id);

    if ($agenda->file_pdf && file_exists(public_path('uploads/undangan/' . $agenda->file_pdf))) {
        unlink(public_path('uploads/undangan/' . $agenda->file_pdf));
    }

    $agenda->delete(); 

    $targetRoute = Auth::user()->role === 'admin' ? 'mading.index' : 'mading.bidang';

    return redirect()->route($targetRoute)->with('success', 'Agenda kegiatan berhasil dihapus.');
}
}