<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission

        // 6. Mata Kuliah Tambahan (semester berbeda)
        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF302'],
            [
                'nama_mk' => 'Basis Data Lanjut',
                'deskripsi' => 'Mata kuliah yang membahas perancangan basis data relasional tingkat lanjut, normalisasi, query optimization, dan transaksi.',
                'sks' => 3,
                'semester' => 'Genap 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'nama_mk' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Mata kuliah yang membahas siklus hidup pengembangan perangkat lunak, analisis kebutuhan, perancangan, pengujian, dan pemeliharaan.',
                'sks' => 3,
                'semester' => 'Ganjil 2027/2028',
                'dosen_id' => $dosen->id,
            ]
        );

        // 7. Mahasiswa Tambahan
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        // Daftarkan mahasiswa ke mata kuliah (approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa2->id => ['status' => 'approved'],
        ]);
        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        // 8. Dua Tugas Contoh
        $assignment1 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Tugas 1: CRUD Sederhana dengan Laravel'],
            [
                'deskripsi' => 'Buatlah aplikasi CRUD sederhana menggunakan Laravel, lengkap dengan migration, model, controller, dan view Blade.',
                'deadline' => now()->addDays(7),
            ]
        );

        $assignment2 = Assignment::updateOrCreate(
            ['course_id' => $course2->id, 'judul' => 'Tugas 1: Normalisasi dan ERD'],
            [
                'deskripsi' => 'Lakukan normalisasi sampai 3NF pada studi kasus yang diberikan, lalu gambarkan ERD-nya.',
                'deadline' => now()->addDays(14),
            ]
        );

        // 9. Contoh Data Submission (satu sudah dinilai, satu belum)
        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa1->id],
            [
                'jawaban' => 'Aplikasi CRUD sudah selesai dibuat. Source code ada di repository GitHub terlampir.',
                'submitted_at' => now()->subDays(2),
                'nilai' => 85,
                'catatan_dosen' => 'Struktur kode sudah rapi. Tambahkan validasi input pada form agar lebih aman.',
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa2->id],
            [
                'jawaban' => 'Aplikasi CRUD dengan fitur tambah, ubah, dan hapus data sudah berjalan.',
                'submitted_at' => now()->subDay(),
                'nilai' => null,
                'catatan_dosen' => null,
            ]
        );
    }
}