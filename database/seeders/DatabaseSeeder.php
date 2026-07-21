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
        // 1. Akun Admin / Sekretariat
        User::create([
            'nama' => 'Admin Sekretariat',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bidang_id' => null,
        ]);

        // 2. Akun Kepala Dinas
        User::create([
            'nama' => 'Drs. H. M. Ramadhan, M.Si',
            'email' => 'kadis@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kadis',
            'bidang_id' => null,
        ]);

        // 3. Akun Staf Bidang PKA (Bidang ID 1)
        User::create([
            'nama' => 'Staf Bidang PKA',
            'email' => 'pka@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 1,
        ]);

        // 4. Akun Staf Bidang PP (Bidang ID 2)
        User::create([
            'nama' => 'Staf Bidang PP',
            'email' => 'pp@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 2,
        ]);

        // 5. Akun Staf Bidang PHA (Bidang ID 3)
        User::create([
            'nama' => 'Staf Bidang PHA',
            'email' => 'pha@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 3,
        ]);

        // 6. Akun Staf Bidang KHP (Bidang ID 4)
        User::create([
            'nama' => 'Staf Bidang KHP',
            'email' => 'khp@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bidang_id' => 4,
        ]);

        // -------------------------------------------------------------
        // DUMMY DATA AGENDA UNTUK MADING (1 AGENDA PER BIDANG)
        // -------------------------------------------------------------
        Agenda::create([
            'no_surat' => '009/KPS-KOM/I/2026',
            'tgl_surat' => '2026-07-20',
            'tgl_diterima' => '2026-07-21',
            'no_agenda' => '066',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'ULM Banjarmasin',
            'perihal' => 'Sosialisasi Anti-Bullying dan Kekerasan Anak di Sekolah',
            'bidang_id' => 1, // Bidang PKA
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Diwakilkan Kabid PKA untuk hadir dan mendampingi.',
        ]);

        Agenda::create([
            'no_surat' => '012/PP-BJM/VII/2026',
            'tgl_surat' => '2026-07-21',
            'tgl_diterima' => '2026-07-21',
            'no_agenda' => '067',
            'sifat_surat' => 'Sangat Segera',
            'surat_dari' => 'Kelurahan Surgi Mufti',
            'perihal' => 'Penyuluhan Pencegahan KDRT Tingkat Kelurahan',
            'bidang_id' => 2, // Bidang PP
            'status_disposisi' => 'Hadir',
            'catatan_kadis' => 'Kadis hadir langsung.',
        ]);

        Agenda::create([
            'no_surat' => '045/FAD-BJM/VII/2026',
            'tgl_surat' => '2026-07-22',
            'tgl_diterima' => '2026-07-22',
            'no_agenda' => '068',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Forum Anak Daerah BJM',
            'perihal' => 'Rapat Pembahasan Hak Sipil dan Kebebasan Anak',
            'bidang_id' => 3, // Bidang PHA
            'status_disposisi' => 'Hadir',
            'catatan_kadis' => 'Kadis hadir membuka acara.',
        ]);

        Agenda::create([
            'no_surat' => '102/KHP-DPA/VII/2026',
            'tgl_surat' => '2026-07-23',
            'tgl_diterima' => '2026-07-23',
            'no_agenda' => '069',
            'sifat_surat' => 'Segera',
            'surat_dari' => 'Tim Penggerak PKK BJM',
            'perihal' => 'Pelatihan Kewirausahaan Perempuan Produk Lokal',
            'bidang_id' => 4, // Bidang KHP
            'status_disposisi' => 'Disposisi',
            'catatan_kadis' => 'Wakilkan ke Kabid KHP.',
        ]);
    }
}