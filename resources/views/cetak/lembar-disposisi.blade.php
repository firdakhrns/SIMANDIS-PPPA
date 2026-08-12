<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Disposisi - {{ $agenda->no_agenda }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        /* TAMPILAN BROWSER : DIPAKSA MENJADI KERTAS A4 DI TENGAH */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13px;
            color: #000;
            line-height: 1.3;
            background-color: #f1f5f9; /* Warna abu-abu background luar kertas */
            margin: 0;
            padding: 30px 0;
            display: flex;
            justify-content: center;
        }

        /* KERTAS A4 PRESISI */
        .page-container {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            padding: 15mm;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .wrapper {
            border: 2px solid #000;
            padding: 0;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
        }

        .kop-table td {
            border: none;
            padding: 8px;
            vertical-align: middle;
        }

        .logo {
            width: 65px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            font-weight: bold;
        }

        .kop-text h3 {
            margin: 0;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .kop-text h2 {
            margin: 2px 0 0 0;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .title-box {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            letter-spacing: 1px;
            padding: 6px 0;
            border-bottom: 1.5px solid #000;
            background-color: #fcfcfc;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .no-border-left { border-left: none !important; }
        .no-border-right { border-right: none !important; }
        .no-border-top { border-top: none !important; }
        .no-border-bottom { border-bottom: none !important; }

        .checkbox-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1.2px solid #000;
            text-align: center;
            line-height: 9px;
            font-size: 11px;
            font-weight: bold;
            margin-right: 5px;
            vertical-align: middle;
        }

        .checkbox-label {
            vertical-align: middle;
        }

        .checkbox-group {
            margin-bottom: 5px;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            margin-top: 14px;
            height: 1px;
            width: 90%;
        }

        .btn-print-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #1a2b4c;
            color: #fff;
            padding: 10px 18px;
            border-radius: 10px;
            font-family: sans-serif;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            border: none;
            z-index: 999;
        }

        .btn-print-floating:hover {
            background-color: #0f172a;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0; 
            }
            body {
                padding: 1.5cm; 
                background: none;
                display: block;
            }
            .page-container {
                width: 100%;
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print-floating no-print">
        🖨️ Cetak / Print Dokumen
    </button>

    @php
        $surat = $agenda->surat;
        $disposisi = $agenda->disposisi;

        $suratDari = $surat->surat_dari ?? $agenda->surat_dari ?? '-';
        $noSurat = $surat->no_surat ?? $agenda->no_surat ?? '-';
        $perihal = $surat->perihal ?? $agenda->perihal ?? '-';
        $sifatSurat = $surat->sifat_surat ?? $agenda->sifat_surat ?? 'Segera';
        
        $tglSurat = $surat->tgl_surat ?? $agenda->tgl_surat ?? now();
        $tglDiterima = $surat->tgl_diterima ?? $agenda->tgl_diterima ?? now();

        $rawTarget = $disposisi->diteruskan_kepada ?? '[]';
        $selectedTarget = is_array($rawTarget) 
            ? $rawTarget 
            : json_decode($rawTarget ?? '[]', true);
        $selectedTarget = $selectedTarget ?? [];
    @endphp

    <div class="page-container">
        <div class="wrapper">
            <table class="kop-table">
                <tr>
                    <td style="width: 70px; text-align: center;">
                        <img src="{{ asset('images/logo-banjarmasin.png') }}" class="logo" alt="Logo Pemko" onerror="this.onerror=null; this.src='{{ asset('pemko.png') }}';">
                    </td>
                    <td class="kop-text" style="padding-right: 70px;">
                        <h3>PEMERINTAH KOTA BANJARMASIN</h3>
                        <h2>DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK</h2>
                    </td>
                </tr>
            </table>

            <div class="title-box">
                LEMBAR DISPOSISI
            </div>

            <table class="content-table">
                <tr>
                    <td style="width: 50%; height: 75px;" class="no-border-left">
                        <strong>Surat Dari :</strong> {{ $suratDari }}<br><br>
                        <strong>No. Surat :</strong> {{ $noSurat }}<br>
                        <strong>Tgl. Surat :</strong> {{ \Carbon\Carbon::parse($tglSurat)->locale('id')->translatedFormat('d F Y') }}
                    </td>
                    <td style="width: 50%;" class="no-border-right">
                        <strong>Diterima Tgl. :</strong> {{ \Carbon\Carbon::parse($tglDiterima)->locale('id')->translatedFormat('d F Y') }}<br>
                        <strong>No. Agenda :</strong> {{ $agenda->no_agenda ?? '-' }}<br>
                        <strong>Sifat :</strong><br>
                        <div style="margin-top: 5px;">
                            <span class="checkbox-group">
                                <span class="checkbox-box">{{ $sifatSurat === 'Sangat Segera' ? '✓' : '' }}</span>
                                <span class="checkbox-label">Sangat Segera</span>
                            </span>
                            &nbsp;
                            <span class="checkbox-group">
                                <span class="checkbox-box">{{ $sifatSurat === 'Segera' ? '✓' : '' }}</span>
                                <span class="checkbox-label">Segera</span>
                            </span>
                            &nbsp;
                            <span class="checkbox-group">
                                <span class="checkbox-box">{{ $sifatSurat === 'Rahasia' ? '✓' : '' }}</span>
                                <span class="checkbox-label">Rahasia</span>
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="height: 45px;" class="no-border-left no-border-right">
                        <strong>Hal :</strong> {{ $perihal }}
                    </td>
                </tr>

                <tr>
                    <td style="height: 170px;" class="no-border-left">
                        <strong>Diteruskan Kepada Sdr. :</strong><br>
                        <div style="margin-top: 6px;" class="checkbox-group">
                            <span class="checkbox-box">{{ in_array('Sekretaris', $selectedTarget) ? '✓' : '' }}</span>
                            <span class="checkbox-label">Sekretaris</span>
                        </div>
                        <div class="checkbox-group">
                            <span class="checkbox-box">{{ in_array('Kabid KHP', $selectedTarget) || $agenda->bidang_id == 4 ? '✓' : '' }}</span>
                            <span class="checkbox-label">Kabid Kualitas Hidup Perempuan</span>
                        </div>
                        <div class="checkbox-group">
                            <span class="checkbox-box">{{ in_array('Kabid PP', $selectedTarget) || $agenda->bidang_id == 2 ? '✓' : '' }}</span>
                            <span class="checkbox-label">Kabid Perlindungan Perempuan</span>
                        </div>
                        <div class="checkbox-group">
                            <span class="checkbox-box">{{ in_array('Kabid PKA', $selectedTarget) || $agenda->bidang_id == 1 ? '✓' : '' }}</span>
                            <span class="checkbox-label">Kabid Perlindungan Khusus Anak</span>
                        </div>
                        <div class="checkbox-group">
                            <span class="checkbox-box">{{ in_array('Kabid PHA', $selectedTarget) || $agenda->bidang_id == 3 ? '✓' : '' }}</span>
                            <span class="checkbox-label">Kabid Pemenuhan Hak Anak</span>
                        </div>
                        <div class="checkbox-group">
                            <span class="checkbox-box">{{ in_array('Kepala UPTD PPA', $selectedTarget) ? '✓' : '' }}</span>
                            <span class="checkbox-label">Kepala UPTD PPA</span>
                        </div>
                        <div style="margin-top: 5px;">
                            Dan seterusnya ...........................................................
                        </div>
                    </td>
                    <td class="no-border-right">
                        <strong>Dengan hormat harap :</strong><br>
                        <div style="margin-top: 6px;" class="checkbox-group">
                            <span class="checkbox-box">{{ (is_array($agenda->instruksi_pimpinan) && in_array('Tanggapan dan Saran Proses', $agenda->instruksi_pimpinan)) ? '✓' : '' }}</span>
                            <span class="checkbox-label">Tanggapan dan Saran Proses</span>
                        </div>
                        <div class="checkbox-group">
                            <span class="checkbox-box">{{ (is_array($agenda->instruksi_pimpinan) && in_array('Lebih Lanjut', $agenda->instruksi_pimpinan)) ? '✓' : '' }}</span>
                            <span class="checkbox-label">Lebih Lanjut</span>
                        </div>
                        <div class="checkbox-group">
                            <span class="checkbox-box">{{ (is_array($agenda->instruksi_pimpinan) && in_array('Koordinasi / konfirmasikan', $agenda->instruksi_pimpinan)) ? '✓' : '' }}</span>
                            <span class="checkbox-label">Koordinasi / konfirmasikan</span>
                        </div>
                        <div class="dotted-line"></div>
                        <div class="dotted-line"></div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="height: 160px;" class="no-border-left no-border-right no-border-bottom">
                        <strong>Catatan Arahan Kepala Dinas :</strong><br>
                        <div style="margin-top: 8px; font-style: italic; font-size: 13px;">
                            {{ $disposisi->catatan_kadis ?? 'Belum ada catatan petunjuk disposisi.' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>