<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agenda;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================
        // 1. AKUN USER
        // ============================================
        
        // Admin / Sekretariat
        User::create([
            'nama' => 'Admin Sekretariat',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bidang_id' => null,
        ]);

        // Kepala Dinas
        User::create([
            'nama' => 'Drs. H. M. Ramadhan, M.Si',
            'email' => 'kadis@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kadis',
            'bidang_id' => null,
        ]);

        // Staf Bidang PKA (Bidang ID 1)
        User::create([
            'nama' => 'Staf Bidang PKA',
            'email' => 'pka@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 1,
        ]);

        // Staf Bidang PP (Bidang ID 2)
        User::create([
            'nama' => 'Staf Bidang PP',
            'email' => 'pp@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 2,
        ]);

        // Staf Bidang PHA (Bidang ID 3)
        User::create([
            'nama' => 'Staf Bidang PHA',
            'email' => 'pha@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 3,
        ]);

        // Staf Bidang KHP (Bidang ID 4)
        User::create([
            'nama' => 'Staf Bidang KHP',
            'email' => 'khp@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 4,
        ]);

        // ============================================
        // 2. DUMMY DATA AGENDA (BANYAK)
        // ============================================
        
        // ---------- BIDANG PKA (ID 1) ----------
        Agenda::create([
            'no_surat' => '001/PPPA/PKA/2026',
            'tgl_surat' => '2026-07-01 09:00:00',
            'tgl_diterima' => '2026-07-01',
            'no_agenda' => 'AGD-001',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Dinas Pendidikan Kota Banjarmasin',
            'perihal' => 'Rapat Koordinasi Program Perlindungan Anak di Sekolah',
            'bidang_id' => 1,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Diwakilkan Kabid PKA.',
        ]);

        Agenda::create([
            'no_surat' => '002/PPPA/PKA/2026',
            'tgl_surat' => '2026-07-05 10:30:00',
            'tgl_diterima' => '2026-07-05',
            'no_agenda' => 'AGD-002',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Polresta Banjarmasin',
            'perihal' => 'Sosialisasi Anti-Bullying dan Kekerasan Anak di Sekolah',
            'bidang_id' => 1,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Hadir',
            'catatan_kadis' => 'Kadis hadir langsung.',
        ]);

        Agenda::create([
            'no_surat' => '003/PPPA/PKA/2026',
            'tgl_surat' => '2026-07-12 08:00:00',
            'tgl_diterima' => '2026-07-12',
            'no_agenda' => 'AGD-003',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Dinas Sosial Kota Banjarmasin',
            'perihal' => 'Pendampingan Anak Korban Kekerasan',
            'bidang_id' => 1,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Tindak lanjut dari laporan masyarakat.',
        ]);

        Agenda::create([
            'no_surat' => '004/PPPA/PKA/2026',
            'tgl_surat' => '2026-07-20 09:00:00',
            'tgl_diterima' => '2026-07-20',
            'no_agenda' => 'AGD-004',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'ULM Banjarmasin',
            'perihal' => 'Sosialisasi Anti-Bullying dan Kekerasan Anak di Sekolah',
            'bidang_id' => 1,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Diwakilkan Kabid PKA untuk hadir dan mendampingi.',
        ]);

        Agenda::create([
            'no_surat' => '005/PPPA/PKA/2026',
            'tgl_surat' => '2026-07-25 13:00:00',
            'tgl_diterima' => '2026-07-25',
            'no_agenda' => 'AGD-005',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Pengadilan Agama Banjarmasin',
            'perihal' => 'Pendampingan Hukum Anak dalam Kasus Perceraian',
            'bidang_id' => 1,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '006/PPPA/PKA/2026',
            'tgl_surat' => '2026-08-02 10:00:00',
            'tgl_diterima' => '2026-08-02',
            'no_agenda' => 'AGD-006',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Puskesmas Banjarmasin',
            'perihal' => 'Pemeriksaan Kesehatan Anak Putus Sekolah',
            'bidang_id' => 1,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        // ---------- BIDANG PP (ID 2) ----------
        Agenda::create([
            'no_surat' => '001/PPPA/PP/2026',
            'tgl_surat' => '2026-07-02 09:30:00',
            'tgl_diterima' => '2026-07-02',
            'no_agenda' => 'AGD-007',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Kelurahan Surgi Mufti',
            'perihal' => 'Penyuluhan Pencegahan KDRT Tingkat Kelurahan',
            'bidang_id' => 2,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Hadir',
            'catatan_kadis' => 'Kadis hadir langsung.',
        ]);

        Agenda::create([
            'no_surat' => '002/PPPA/PP/2026',
            'tgl_surat' => '2026-07-08 14:00:00',
            'tgl_diterima' => '2026-07-08',
            'no_agenda' => 'AGD-008',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'DP3A Provinsi Kalsel',
            'perihal' => 'Rapat Koordinasi Penanganan Kasus KDRT',
            'bidang_id' => 2,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Diwakilkan Kabid PP.',
        ]);

        Agenda::create([
            'no_surat' => '003/PPPA/PP/2026',
            'tgl_surat' => '2026-07-15 08:30:00',
            'tgl_diterima' => '2026-07-15',
            'no_agenda' => 'AGD-009',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Rumah Sakit Ulin Banjarmasin',
            'perihal' => 'Penanganan Medis Korban KDRT',
            'bidang_id' => 2,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '004/PPPA/PP/2026',
            'tgl_surat' => '2026-07-22 11:00:00',
            'tgl_diterima' => '2026-07-22',
            'no_agenda' => 'AGD-010',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Lembaga Bantuan Hukum Banjarmasin',
            'perihal' => 'Pendampingan Hukum Korban KDRT',
            'bidang_id' => 2,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '005/PPPA/PP/2026',
            'tgl_surat' => '2026-08-01 10:00:00',
            'tgl_diterima' => '2026-08-01',
            'no_agenda' => 'AGD-011',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Kepolisian Sektor Banjarmasin',
            'perihal' => 'Koordinasi Penanganan Kasus KDRT',
            'bidang_id' => 2,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        // ---------- BIDANG PHA (ID 3) ----------
        Agenda::create([
            'no_surat' => '001/PPPA/PHA/2026',
            'tgl_surat' => '2026-07-03 13:30:00',
            'tgl_diterima' => '2026-07-03',
            'no_agenda' => 'AGD-012',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Forum Anak Daerah BJM',
            'perihal' => 'Rapat Pembahasan Hak Sipil dan Kebebasan Anak',
            'bidang_id' => 3,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Hadir',
            'catatan_kadis' => 'Kadis hadir membuka acara.',
        ]);

        Agenda::create([
            'no_surat' => '002/PPPA/PHA/2026',
            'tgl_surat' => '2026-07-09 09:00:00',
            'tgl_diterima' => '2026-07-09',
            'no_agenda' => 'AGD-013',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Dinas Kesehatan Kota Banjarmasin',
            'perihal' => 'Program Imunisasi Anak Sekolah',
            'bidang_id' => 3,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Koordinasikan dengan Dinas Kesehatan.',
        ]);

        Agenda::create([
            'no_surat' => '003/PPPA/PHA/2026',
            'tgl_surat' => '2026-07-16 10:30:00',
            'tgl_diterima' => '2026-07-16',
            'no_agenda' => 'AGD-014',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Bappeda Kota Banjarmasin',
            'perihal' => 'Penyusunan Program Hak Anak',
            'bidang_id' => 3,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '004/PPPA/PHA/2026',
            'tgl_surat' => '2026-07-23 14:00:00',
            'tgl_diterima' => '2026-07-23',
            'no_agenda' => 'AGD-015',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Komisi Perlindungan Anak Indonesia (KPAI)',
            'perihal' => 'Audit dan Evaluasi Pemenuhan Hak Anak',
            'bidang_id' => 3,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '005/PPPA/PHA/2026',
            'tgl_surat' => '2026-08-03 08:00:00',
            'tgl_diterima' => '2026-08-03',
            'no_agenda' => 'AGD-016',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'DP3A Kota Banjarmasin',
            'perihal' => 'Rapat Evaluasi Program Pemenuhan Hak Anak',
            'bidang_id' => 3,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        // ---------- BIDANG KHP (ID 4) ----------
        Agenda::create([
            'no_surat' => '001/PPPA/KHP/2026',
            'tgl_surat' => '2026-07-04 09:00:00',
            'tgl_diterima' => '2026-07-04',
            'no_agenda' => 'AGD-017',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Tim Penggerak PKK BJM',
            'perihal' => 'Pelatihan Kewirausahaan Perempuan Produk Lokal',
            'bidang_id' => 4,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Wakilkan ke Kabid KHP.',
        ]);

        Agenda::create([
            'no_surat' => '002/PPPA/KHP/2026',
            'tgl_surat' => '2026-07-10 13:00:00',
            'tgl_diterima' => '2026-07-10',
            'no_agenda' => 'AGD-018',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Dinas Perdagangan Kota Banjarmasin',
            'perihal' => 'Program Pemberdayaan Perempuan Pengusaha UMKM',
            'bidang_id' => 4,
            'file_pdf' => null,
            'status_pelaksanaan' => 'terlaksana',
            'status_disposisi' => 'Hadir',
            'catatan_kadis' => 'Kadis hadir memberikan sambutan.',
        ]);

        Agenda::create([
            'no_surat' => '003/PPPA/KHP/2026',
            'tgl_surat' => '2026-07-17 10:00:00',
            'tgl_diterima' => '2026-07-17',
            'no_agenda' => 'AGD-019',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Bank Indonesia Banjarmasin',
            'perihal' => 'Pelatihan Literasi Keuangan Perempuan',
            'bidang_id' => 4,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '004/PPPA/KHP/2026',
            'tgl_surat' => '2026-07-24 08:30:00',
            'tgl_diterima' => '2026-07-24',
            'no_agenda' => 'AGD-020',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'BPS Kota Banjarmasin',
            'perihal' => 'Pendataan dan Analisis Kualitas Hidup Perempuan',
            'bidang_id' => 4,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '005/PPPA/KHP/2026',
            'tgl_surat' => '2026-08-04 14:00:00',
            'tgl_diterima' => '2026-08-04',
            'no_agenda' => 'AGD-021',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Kementerian Pemberdayaan Perempuan RI',
            'perihal' => 'Rapat Koordinasi Nasional Pemberdayaan Perempuan',
            'bidang_id' => 4,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        // ---------- AGENDA CAMPURAN (BIDANG BERBEDA) ----------
        Agenda::create([
            'no_surat' => '006/PPPA/PKA/2026',
            'tgl_surat' => '2026-08-05 09:00:00',
            'tgl_diterima' => '2026-08-05',
            'no_agenda' => 'AGD-022',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'DP3A Kota Banjarmasin',
            'perihal' => 'Rapat Koordinasi Lintas Bidang',
            'bidang_id' => 1,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '006/PPPA/PP/2026',
            'tgl_surat' => '2026-08-06 10:00:00',
            'tgl_diterima' => '2026-08-06',
            'no_agenda' => 'AGD-023',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Komnas Perempuan RI',
            'perihal' => 'Audit dan Evaluasi Penanganan Kasus Perempuan',
            'bidang_id' => 2,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '006/PPPA/PHA/2026',
            'tgl_surat' => '2026-08-07 11:00:00',
            'tgl_diterima' => '2026-08-07',
            'no_agenda' => 'AGD-024',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'UNICEF Indonesia',
            'perihal' => 'Monitoring Program Hak Anak di Kota Banjarmasin',
            'bidang_id' => 3,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);

        Agenda::create([
            'no_surat' => '006/PPPA/KHP/2026',
            'tgl_surat' => '2026-08-08 13:00:00',
            'tgl_diterima' => '2026-08-08',
            'no_agenda' => 'AGD-025',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Kementerian Kesehatan RI',
            'perihal' => 'Program Kesehatan Reproduksi Perempuan',
            'bidang_id' => 4,
            'file_pdf' => null,
            'status_pelaksanaan' => 'belum',
            'status_disposisi' => null,
            'catatan_kadis' => null,
        ]);
    }
}