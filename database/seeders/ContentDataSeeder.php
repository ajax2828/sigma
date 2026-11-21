<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\HeaderSlide;
use App\Models\Member;
use Illuminate\Database\Seeder;

class ContentDataSeeder extends Seeder
{
    /**
     * Isi tabel konten yang dibiarkan kosong saat seeding awal.
     * Digunakan karena tabel members/achievements/header_slides/posts
     * tidak pernah diisi otomatis dari seeder sebelumnya, sehingga
     * landing page mengeluarkan placeholder "Belum ada..." dan tampak kosong.
     */
    public function run(): void
    {
        $this->seedMembers();
        $this->seedMembersHistory();
        $this->seedAchievements();
    }

    private function seedMembers(): void
    {
        $members = [
            ['initial' => 'AI', 'name' => 'Ahmad Ihsan', 'role' => 'Ketua Organisasi', 'code' => 'Giyama/2025/001', 'description' => 'Mahasiswa Teknik Informatika yang memiliki pengalaman memimpin tim pengembangan aplikasi web dan mobile selama 3 tahun.', 'photo' => null, 'sort_order' => 1],
            ['initial' => 'BN', 'name' => 'Bella Nadira', 'role' => 'Wakil Ketua', 'code' => 'Giyama/2025/002', 'description' => 'Spesialis UI/UX Design dengan portfolio lebih dari 20 proyek desain untuk startup dan perusahaan lokal.', 'photo' => null, 'sort_order' => 2],
            ['initial' => 'CR', 'name' => 'Candra Rizky', 'role' => 'Sekretaris', 'code' => 'Giyama/2025/003', 'description' => 'Mengelola administrasi organisasi dan koordinasi event, aktif di kepanitiaan tingkat universitas.', 'photo' => null, 'sort_order' => 3],
            ['initial' => 'DP', 'name' => 'Dian Permata', 'role' => 'Bendahara', 'code' => 'Giyama/2025/004', 'description' => 'Ahli keuangan yang mengatur anggaran dan sponsorship, berpengalaman di organisasi kemahasiswaan.', 'photo' => null, 'sort_order' => 4],
            ['initial' => 'EH', 'name' => 'Eka Hidayat', 'role' => 'K Divisi Teknologi', 'code' => 'Giyama/2025/005', 'description' => 'Full-stack developer dengan keahlian Laravel, React, dan Flutter. Juara 2 Hackathon Nasional 2025.', 'photo' => null, 'sort_order' => 5],
            ['initial' => 'FA', 'name' => 'Fitri Aulia', 'role' => 'K Divisi Desain', 'code' => 'Giyama/2025/006', 'description' => 'Desainer grafis profesional, berpengalaman membuat branding dan konten visual untuk berbagai komunitas.', 'photo' => null, 'sort_order' => 6],
            ['initial' => 'GH', 'name' => 'Galih Prasetyo', 'role' => 'K Divisi Humas', 'code' => 'Giyama/2025/007', 'description' => 'Menangani hubungan masyarakat, media sosial, dan kerjasama eksternal dengan berbagai stakeholder.', 'photo' => null, 'sort_order' => 7],
            ['initial' => 'HN', 'name' => 'Hana Nurhaliza', 'role' => 'Staf Ahli', 'code' => 'Giyama/2025/008', 'description' => 'Mahasiswa Ilmu Komputer yang aktif mengajar programming dasar untuk anggota baru setiap semester.', 'photo' => null, 'sort_order' => 8],
        ];

        foreach ($members as $m) {
            Member::updateOrCreate(
                ['code' => $m['code']],
                [
                    'initial' => $m['initial'],
                    'name' => $m['name'],
                    'role' => $m['role'],
                    'code' => $m['code'],
                    'description' => $m['description'],
                    'photo' => $m['photo'],
                    'sort_order' => $m['sort_order'],
                ]
            );
        }
    }

    private function seedMembersHistory(): void
    {
        $members = Member::orderBy('sort_order')->orderBy('id')->get();
        $action = 'created-by-seed';
        foreach ($members as $member) {
            \App\Models\MemberHistory::create([
                'member_id' => $member->id,
                'action' => $action,
                'initial' => $member->initial,
                'name' => $member->name,
                'role' => $member->role,
                'code' => $member->code,
                'description' => $member->description,
                'photo' => $member->photo,
                'created_by' => null,
            ]);
        }
    }

    private function seedAchievements(): void
    {
        $achievements = [
            ['icon' => '🏆', 'title' => 'Juara 1 - Nasional Innovation Challenge 2025', 'year' => '2025', 'description' => 'Kompetisi inovasi teknologi tingkat nasional yang diselenggarakan oleh Kementerian Pendidikan. Tim SIGMA membawa aplikasi manajemen sampah berbasis AI.', 'image' => null, 'sort_order' => 1],
            ['icon' => '🥈', 'title' => 'Juara 2 - Hackathon Indonesia Digital 2025', 'year' => '2025', 'description' => 'Hackathon 48 jam dengan tema "Digital Solutions for Education". Tim berhasil membuat platform e-learning interaktif.', 'image' => null, 'sort_order' => 2],
            ['icon' => '🥉', 'title' => 'Juara 3 - Lomba Cipta Aplikasi 2024', 'year' => '2024', 'description' => 'Lomba pembuatan aplikasi tingkat provinsi yang diikuti 50+ tim dari berbagai universitas ternama.', 'image' => null, 'sort_order' => 3],
            ['icon' => '🎖️', 'title' => 'Best Innovation Award - Tech Fest 2025', 'year' => '2025', 'description' => 'Penghargaan inovasi terbaik dalam acara festival teknologi yang diselenggarakan universitas negeri ternama.', 'image' => null, 'sort_order' => 4],
            ['icon' => '🌟', 'title' => '1000 Startup Digital - Top 100', 'year' => '2024', 'description' => 'Berhasil masuk 100 besar program pencetakan startup digital yang diselenggarakan Kominfo Indonesia.', 'image' => null, 'sort_order' => 5],
            ['icon' => '📜', 'title' => 'Sertifikasi Internasional - Google IT Support', 'year' => '2025', 'description' => '15 anggota berhasil mendapatkan sertifikasi Google IT Support sebagai bukti kompetensi di bidang teknologi informasi.', 'image' => null, 'sort_order' => 6],
            ['icon' => '🚀', 'title' => 'Most Active Community - Developer Circle', 'year' => '2024', 'description' => 'Diakui oleh Facebook Developer Circle sebagai komunitas teknologi paling aktif di regional masing-masing.', 'image' => null, 'sort_order' => 7],
            ['icon' => '💡', 'title' => 'Best Paper - Seminar Nasional Informatika', 'year' => '2024', 'description' => 'Publikasi jurnal ilmiah tentang implementasi machine learning untuk klasifikasi penyakit tanaman.', 'image' => null, 'sort_order' => 8],
        ];

        foreach ($achievements as $a) {
            Achievement::updateOrCreate(
                ['title' => $a['title']],
                [
                    'icon' => $a['icon'],
                    'title' => $a['title'],
                    'year' => $a['year'],
                    'description' => $a['description'],
                    'image' => $a['image'],
                    'sort_order' => $a['sort_order'],
                ]
            );
        }
    }
}
