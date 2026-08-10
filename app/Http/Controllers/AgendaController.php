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
            'no_surat'       => [
                'required', 
                'string', 
                'max:100', 
                'regex:/^[a-zA-Z0-9\s\/\.\-]+$/'
            ],
            'no_agenda'      => 'required|string|max:50',
            'tgl_surat_date' => 'required|date',
            'tgl_surat_time' => 'required',
            'tgl_diterima'   => 'required|date',
            'surat_dari'     => [
                'required', 
                'string', 
                'max:255', 
                'regex:/^[a-zA-Z0-9\s\.\,\-\(\)]+$/'
            ],
            'sifat_surat'    => 'required|in:Segera,Sangat Segera,Rahasia',
            'perihal'        => [
                'required', 
                'string', 
                'max:1000', 
                'regex:/^[a-zA-Z0-9\s\.\,\-\/\(\)]+$/'
            ],
            'bidang_id'      => 'required|integer|in:1,2,3,4',
            'file_pdf'       => 'nullable|file|mimes:pdf,doc,docx|max:51200',
        ], [
            'no_surat.regex'   => 'Nomor surat hanya boleh berisi huruf, angka, serta simbol garis miring (/), titik (.), dan strip (-).',
            'surat_dari.regex' => 'Nama pengirim hanya boleh berisi huruf, angka, spasi, titik (.), koma (,), dan tanda kurung ().',
            'perihal.regex'    => 'Isi perihal tidak boleh mengandung karakter khusus simbol tak terduga.',
            'file_pdf.mimes'   => 'Format berkas harus berupa dokumen PDF, DOC, atau DOCX.',
            'file_pdf.max'     => 'Ukuran berkas tidak boleh melebihi 50 MB.',
        ]);

        $validated['tgl_surat'] = $request->tgl_surat_date . ' ' . $request->tgl_surat_time;

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $request->no_surat) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/surat', $fileName);
            $validated['file_pdf'] = $fileName;
        }

        Agenda::create($validated);

        return redirect()->route('mading.index')->with('success', 'Agenda kegiatan berhasil diregistrasi.');
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