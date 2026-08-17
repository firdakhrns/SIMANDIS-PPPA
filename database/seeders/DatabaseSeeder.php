<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Surat;
use App\Models\Agenda;
use App\Models\Disposisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AKUN USER
        User::create([
            'nama' => 'Admin Sekretariat',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('sekretariat'),
            'role' => 'admin',
            'bidang_id' => null,
        ]);

        User::create([
            'nama' => 'Dr. H. Muhammad Ramadhan, SE., ME., Ak.',
            'email' => 'kadis@gmail.com',
            'password' => Hash::make('kadis'),
            'role' => 'kadis',
            'bidang_id' => null,
        ]);

        User::create(['nama' => 'Staf Bidang PKA', 'email' => 'pka@gmail.com', 'password' => Hash::make('bidangpka'), 'role' => 'user', 'bidang_id' => 1]);
        User::create(['nama' => 'Staf Bidang PP', 'email' => 'pp@gmail.com', 'password' => Hash::make('bidangpp'), 'role' => 'user', 'bidang_id' => 2]);
        User::create(['nama' => 'Staf Bidang PHA', 'email' => 'pha@gmail.com', 'password' => Hash::make('bidangpha'), 'role' => 'user', 'bidang_id' => 3]);
        User::create(['nama' => 'Staf Bidang KHP', 'email' => 'khp@gmail.com', 'password' => Hash::make('bidangkhp'), 'role' => 'user', 'bidang_id' => 4]);

        $dummyAgendas = [
            ['no_surat' => '001/PPPA/PKA/2026', 'tgl' => '2026-07-01 09:00:00', 'dari' => 'Dinas Pendidikan Kota Banjarmasin', 'hal' => 'Rapat Koordinasi Program Perlindungan Anak di Sekolah', 'bidang' => 1, 'st' => 'terlaksana', 'disp' => 'Disposisi', 'cat' => 'Diwakilkan Kabid PKA.'],
            ['no_surat' => '002/PPPA/PKA/2026', 'tgl' => '2026-07-05 10:30:00', 'dari' => 'Polresta Banjarmasin', 'hal' => 'Sosialisasi Anti-Bullying dan Kekerasan Anak di Sekolah', 'bidang' => 1, 'st' => 'terlaksana', 'disp' => 'Hadir', 'cat' => 'Kadis hadir langsung.'],
            ['no_surat' => '003/PPPA/PKA/2026', 'tgl' => '2026-07-12 08:00:00', 'dari' => 'Dinas Sosial Kota Banjarmasin', 'hal' => 'Pendampingan Anak Korban Kekerasan', 'bidang' => 1, 'st' => 'terlaksana', 'disp' => 'Disposisi', 'cat' => 'Tindak lanjut laporan.'],
            ['no_surat' => '004/PPPA/PKA/2026', 'tgl' => '2026-07-20 09:00:00', 'dari' => 'ULM Banjarmasin', 'hal' => 'Sosialisasi Anti-Bullying dan Kekerasan Anak', 'bidang' => 1, 'st' => 'belum', 'disp' => 'Disposisi', 'cat' => 'Diwakilkan Kabid PKA.'],
            ['no_surat' => '005/PPPA/PKA/2026', 'tgl' => '2026-07-25 13:00:00', 'dari' => 'Pengadilan Agama Banjarmasin', 'hal' => 'Pendampingan Hukum Anak Kasus Perceraian', 'bidang' => 1, 'st' => 'belum', 'disp' => null, 'cat' => null],
            
            ['no_surat' => '001/PPPA/PP/2026', 'tgl' => '2026-07-02 09:30:00', 'dari' => 'Kelurahan Surgi Mufti', 'hal' => 'Penyuluhan Pencegahan KDRT Kelurahan', 'bidang' => 2, 'st' => 'terlaksana', 'disp' => 'Hadir', 'cat' => 'Kadis hadir langsung.'],
            ['no_surat' => '002/PPPA/PP/2026', 'tgl' => '2026-07-08 14:00:00', 'dari' => 'DP3A Provinsi Kalsel', 'hal' => 'Rapat Koordinasi Penanganan Kasus KDRT', 'bidang' => 2, 'st' => 'terlaksana', 'disp' => 'Disposisi', 'cat' => 'Diwakilkan Kabid PP.'],
            ['no_surat' => '003/PPPA/PP/2026', 'tgl' => '2026-07-15 08:30:00', 'dari' => 'Rumah Sakit Ulin Banjarmasin', 'hal' => 'Penanganan Medis Korban KDRT', 'bidang' => 2, 'st' => 'belum', 'disp' => null, 'cat' => null],
            
            ['no_surat' => '001/PPPA/PHA/2026', 'tgl' => '2026-07-03 13:30:00', 'dari' => 'Forum Anak Daerah BJM', 'hal' => 'Rapat Pembahasan Hak Sipil dan Kebebasan Anak', 'bidang' => 3, 'st' => 'terlaksana', 'disp' => 'Hadir', 'cat' => 'Kadis hadir membuka acara.'],
            ['no_surat' => '002/PPPA/PHA/2026', 'tgl' => '2026-07-09 09:00:00', 'dari' => 'Dinas Kesehatan Kota Banjarmasin', 'hal' => 'Program Imunisasi Anak Sekolah', 'bidang' => 3, 'st' => 'terlaksana', 'disp' => 'Disposisi', 'cat' => 'Koordinasikan dengan Dinkes.'],

            ['no_surat' => '001/PPPA/KHP/2026', 'tgl' => '2026-07-04 09:00:00', 'dari' => 'Tim Penggerak PKK BJM', 'hal' => 'Pelatihan Kewirausahaan Perempuan Produk Lokal', 'bidang' => 4, 'st' => 'terlaksana', 'disp' => 'Disposisi', 'cat' => 'Wakilkan ke Kabid KHP.'],
            ['no_surat' => '002/PPPA/KHP/2026', 'tgl' => '2026-07-10 13:00:00', 'dari' => 'Dinas Perdagangan Kota Banjarmasin', 'hal' => 'Program Pemberdayaan Perempuan Pengusaha UMKM', 'bidang' => 4, 'st' => 'terlaksana', 'disp' => 'Hadir', 'cat' => 'Kadis hadir memberikan sambutan.'],
        ];

        foreach ($dummyAgendas as $idx => $item) {
            $surat = Surat::create([
                'no_surat' => $item['no_surat'],
                'tgl_surat' => $item['tgl'],
                'tgl_diterima' => substr($item['tgl'], 0, 10),
                'sifat_surat' => 'Segera',
                'surat_dari' => $item['dari'],
                'perihal' => $item['hal'],
                'file_pdf' => null,
            ]);

            $agenda = Agenda::create([
                'surat_id' => $surat->id,
                'no_agenda' => 'AGD-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                'bidang_id' => $item['bidang'],
                'tgl_kegiatan' => substr($item['tgl'], 0, 10),
                'jam_kegiatan' => substr($item['tgl'], 11, 8),
                'status_pelaksanaan' => $item['st'],
            ]);

            Disposisi::create([
                'agenda_id' => $agenda->id,
                'status_disposisi' => $item['disp'],
                'catatan_kadis' => $item['cat'],
            ]);
        }
    }
}