<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Disposisi - {{ $agenda->no_agenda }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
        }
        /* Outer Border Frame */
        .wrapper {
            border: 2px solid #000;
            padding: 0;
            margin: 0;
        }
        /* Kop Header Table */
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
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .kop-text h2 {
            margin: 3px 0 0 0;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        /* Title Box */
        .title-box {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 2px;
            padding: 8px 0;
            border-bottom: 1.5px solid #000;
            background-color: #fcfcfc;
        }
        /* Main Content Grid Table */
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

        /* Checkbox Styling */
        .checkbox-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1.2px solid #000;
            text-align: center;
            line-height: 10px;
            font-size: 10px;
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
            margin-top: 12px;
            height: 1px;
            width: 90%;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <!-- 📌 KOP SURAT BERDAMPINGAN DENGAN LOGO PEMKO -->
        <table class="kop-table">
            <tr>
                <td style="width: 80px; text-align: center;">
                    <!-- Gambar diambil langsung dari folder public/pemko.png -->
                    <img src="{{ public_path('pemko.png') }}" class="logo" alt="Logo Pemko">
                </td>
                <td class="kop-text" style="padding-right: 80px;">
                    <h3>PEMERINTAH KOTA BANJARMASIN</h3>
                    <h2>DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK</h2>
                </td>
            </tr>
        </table>

        <!-- 📌 JUDUL DOKUMEN -->
        <div class="title-box">
            LEMBAR DISPOSISI
        </div>

        <!-- 📌 TABEL FORMULIR DISPOSISI -->
        <table class="content-table">
            <!-- BARIS 1: INFORMASI SURAT & AGENDANYA -->
            <tr>
                <td style="width: 50%; height: 75px;" class="no-border-left">
                    <strong>Surat Dari :</strong> {{ $agenda->surat_dari }}<br><br>
                    <strong>No. Surat :</strong> {{ $agenda->no_surat }}<br>
                    <strong>Tgl. Surat :</strong> {{ \Carbon\Carbon::parse($agenda->tgl_surat)->translatedFormat('d F Y') }}
                </td>
                <td style="width: 50%;" class="no-border-right">
                    <strong>Diterima Tgl. :</strong> {{ \Carbon\Carbon::parse($agenda->tgl_diterima)->translatedFormat('d F Y') }}<br>
                    <strong>No. Agenda :</strong> {{ $agenda->no_agenda }}<br>
                    <strong>Sifat :</strong><br>
                    <div style="margin-top: 5px;">
                        <span class="checkbox-group">
                            <span class="checkbox-box">{{ $agenda->sifat_surat === 'Sangat Segera' ? '✓' : '' }}</span>
                            <span class="checkbox-label">Sangat Segera</span>
                        </span>
                        &nbsp;
                        <span class="checkbox-group">
                            <span class="checkbox-box">{{ $agenda->sifat_surat === 'Segera' ? '✓' : '' }}</span>
                            <span class="checkbox-label">Segera</span>
                        </span>
                        &nbsp;
                        <span class="checkbox-group">
                            <span class="checkbox-box">{{ $agenda->sifat_surat === 'Rahasia' ? '✓' : '' }}</span>
                            <span class="checkbox-label">Rahasia</span>
                        </span>
                    </div>
                </td>
            </tr>

            <!-- BARIS 2: PERIHAL / HAL -->
            <tr>
                <td colspan="2" style="height: 50px;" class="no-border-left no-border-right">
                    <strong>Hal :</strong> {{ $agenda->perihal }}
                </td>
            </tr>

            <!-- BARIS 3: DITERUSKAN KEPADA & INSTRUKSI HARAP -->
            <tr>
                <td style="height: 180px;" class="no-border-left">
                    <strong>Diteruskan Kepada Sdr. :</strong><br>
                    <div style="margin-top: 6px;" class="checkbox-group">
                        <span class="checkbox-box">{{ (is_array($agenda->diteruskan_kepada) && in_array('Sekretaris', $agenda->diteruskan_kepada)) ? '✓' : '' }}</span>
                        <span class="checkbox-label">Sekretaris</span>
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox-box">{{ (is_array($agenda->diteruskan_kepada) && in_array('Kabid Kualitas Hidup Perempuan', $agenda->diteruskan_kepada)) || $agenda->bidang_id == 4 ? '✓' : '' }}</span>
                        <span class="checkbox-label">Kabid Kualitas Hidup Perempuan</span>
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox-box">{{ (is_array($agenda->diteruskan_kepada) && in_array('Kabid Perlindungan Perempuan', $agenda->diteruskan_kepada)) || $agenda->bidang_id == 2 ? '✓' : '' }}</span>
                        <span class="checkbox-label">Kabid Perlindungan Perempuan</span>
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox-box">{{ (is_array($agenda->diteruskan_kepada) && in_array('Kabid Perlindungan Khusus Anak', $agenda->diteruskan_kepada)) || $agenda->bidang_id == 1 ? '✓' : '' }}</span>
                        <span class="checkbox-label">Kabid Perlindungan Khusus Anak</span>
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox-box">{{ (is_array($agenda->diteruskan_kepada) && in_array('Kabid Pemenuhan Hak Anak', $agenda->diteruskan_kepada)) || $agenda->bidang_id == 3 ? '✓' : '' }}</span>
                        <span class="checkbox-label">Kabid Pemenuhan Hak Anak</span>
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox-box">{{ (is_array($agenda->diteruskan_kepada) && in_array('Kepala UPTD PPA', $agenda->diteruskan_kepada)) ? '✓' : '' }}</span>
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

            <!-- BARIS 4: CATATAN KADIS -->
            <tr>
                <td colspan="2" style="height: 180px;" class="no-border-left no-border-right no-border-bottom">
                    <strong>Catatan :</strong><br>
                    <div style="margin-top: 8px; font-style: italic; font-size: 11px;">
                        {{ $agenda->catatan_kadis ?? '-' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>