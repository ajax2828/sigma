<?php

namespace Database\Seeders;

use App\Models\LandingContent;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Hero
            ['key' => 'site_title', 'value' => 'SIGMA - Sistem Informasi Terpadu', 'type' => 'text'],
            ['key' => 'hero_title', 'value' => 'SIGMA', 'type' => 'text'],
            ['key' => 'hero_tagline', 'value' => 'Sistem Informasi dan Manajemen Terpadu', 'type' => 'text'],
            ['key' => 'hero_description', 'value' => 'Organisasi mahasiswa yang bergerak di bidang teknologi dan informasi, berkomitmen menghasilkan karya inovatif dan berkontribusi untuk kemajuan teknologi di Indonesia.', 'type' => 'textarea'],
            ['key' => 'hero_cta_label', 'value' => 'Pelajari Lebih', 'type' => 'text'],
            ['key' => 'hero_background_image', 'value' => null, 'type' => 'image'],
            ['key' => 'page_background_image', 'value' => null, 'type' => 'image'],
            ['key' => 'page_gradient_start', 'value' => '#f4f0e6', 'type' => 'text'],
            ['key' => 'page_gradient_end', 'value' => '#e8e0d1', 'type' => 'text'],
            ['key' => 'page_gradient_angle', 'value' => '180', 'type' => 'text'],
            ['key' => 'hero_gradient_start', 'value' => '#f4f0e6', 'type' => 'text'],
            ['key' => 'hero_gradient_end', 'value' => '#faf7ef', 'type' => 'text'],
            ['key' => 'hero_gradient_angle', 'value' => '90', 'type' => 'text'],
            ['key' => 'about_background_image', 'value' => null, 'type' => 'image'],
            ['key' => 'about_gradient_start', 'value' => '#fffaf0', 'type' => 'text'],
            ['key' => 'about_gradient_end', 'value' => '#f4f0e6', 'type' => 'text'],
            ['key' => 'about_gradient_angle', 'value' => '180', 'type' => 'text'],
            ['key' => 'members_background_image', 'value' => null, 'type' => 'image'],
            ['key' => 'members_gradient_start', 'value' => '#e8e0d1', 'type' => 'text'],
            ['key' => 'members_gradient_end', 'value' => '#f8f5ed', 'type' => 'text'],
            ['key' => 'members_gradient_angle', 'value' => '180', 'type' => 'text'],
            ['key' => 'achievements_background_image', 'value' => null, 'type' => 'image'],
            ['key' => 'achievements_gradient_start', 'value' => '#f8f5ed', 'type' => 'text'],
            ['key' => 'achievements_gradient_end', 'value' => '#f4f0e6', 'type' => 'text'],
            ['key' => 'achievements_gradient_angle', 'value' => '180', 'type' => 'text'],
            ['key' => 'footer_background_image', 'value' => null, 'type' => 'image'],
            ['key' => 'footer_gradient_start', 'value' => '#e8e0d1', 'type' => 'text'],
            ['key' => 'footer_gradient_end', 'value' => '#f4f0e6', 'type' => 'text'],
            ['key' => 'footer_gradient_angle', 'value' => '90', 'type' => 'text'],

            // Navigation and footer
            ['key' => 'nav_brand', 'value' => 'SIGMA', 'type' => 'text'],
            ['key' => 'nav_about_label', 'value' => 'About', 'type' => 'text'],
            ['key' => 'nav_members_label', 'value' => 'Anggota', 'type' => 'text'],
            ['key' => 'nav_achievements_label', 'value' => 'Prestasi', 'type' => 'text'],
            ['key' => 'nav_login_label', 'value' => 'Login', 'type' => 'text'],
            ['key' => 'footer_text', 'value' => '© 2026 Organisasi SIGMA. All rights reserved.', 'type' => 'text'],

            // About
            ['key' => 'about_title', 'value' => 'Tentang Kami', 'type' => 'text'],
            ['key' => 'about_description', 'value' => "SIGMA (Sistem Informasi dan Manajemen Terpadu) adalah sebuah organisasi mahasiswa yang berfokus pada pengembangan teknologi informasi. Kami beranggotakan para mahasiswa yang memiliki passion di bidang pemrograman, desain, dan manajemen proyek teknologi.\n\nDidirikan sejak tahun 2024, SIGMA telah menghasilkan berbagai produk digital dan telah berkontribusi dalam berbagai kompetisi teknologi tingkat nasional.", 'type' => 'textarea'],
            ['key' => 'about_subtitle', 'value' => 'Kenali lebih dalam tentang organisasi kami, visi, misi, dan nilai-nilai yang kami pegang.', 'type' => 'text'],
            ['key' => 'about_organization_title', 'value' => 'Organisasi SIGMA', 'type' => 'text'],
            ['key' => 'about_slide_title', 'value' => 'Tentang Kami', 'type' => 'text'],
            ['key' => 'vision_mission_title', 'value' => 'Visi & Misi', 'type' => 'text'],
            ['key' => 'vision', 'value' => 'Menjadi organisasi teknologi terdepan yang menghasilkan inovator-inovator muda berintegritas.', 'type' => 'textarea'],
            ['key' => 'mission', 'value' => 'Membangun ekosistem pembelajaran teknologi yang kolaboratif, inklusif, dan berdampak positif bagi masyarakat.', 'type' => 'textarea'],

            // Stats
            ['key' => 'stat_active_members', 'value' => '25+', 'type' => 'text'],
            ['key' => 'stat_projects', 'value' => '12', 'type' => 'text'],
            ['key' => 'stat_awards', 'value' => '8', 'type' => 'text'],
            ['key' => 'stat_years', 'value' => '3', 'type' => 'text'],
            ['key' => 'stat_partnerships', 'value' => '15+', 'type' => 'text'],
            ['key' => 'stat_dedication', 'value' => '100%', 'type' => 'text'],
            ['key' => 'stat_active_members_label', 'value' => 'Anggota Aktif', 'type' => 'text'],
            ['key' => 'stat_projects_label', 'value' => 'Proyek Selesai', 'type' => 'text'],
            ['key' => 'stat_awards_label', 'value' => 'Penghargaan', 'type' => 'text'],
            ['key' => 'stat_years_label', 'value' => 'Tahun Berdiri', 'type' => 'text'],
            ['key' => 'stat_partnerships_label', 'value' => 'Kerjasama', 'type' => 'text'],
            ['key' => 'stat_dedication_label', 'value' => 'Dedikasi', 'type' => 'text'],

            // Members section
            ['key' => 'member_section_title', 'value' => 'Anggota Kami', 'type' => 'text'],
            ['key' => 'member_section_subtitle', 'value' => 'Orang-orang hebat yang berada di balik setiap pencapaian organisasi.', 'type' => 'text'],

            // Member 1
            ['key' => 'member_initial_1', 'value' => 'AI', 'type' => 'text'],
            ['key' => 'member_name_1', 'value' => 'Ahmad Ihsan', 'type' => 'text'],
            ['key' => 'member_role_1', 'value' => 'Ketua Organisasi', 'type' => 'text'],
            ['key' => 'member_code_1', 'value' => 'Giyama/2025/001', 'type' => 'text'],
            ['key' => 'member_desc_1', 'value' => 'Mahasiswa Teknik Informatika yang memiliki pengalaman memimpin tim pengembangan aplikasi web dan mobile selama 3 tahun.', 'type' => 'textarea'],

            // Member 2
            ['key' => 'member_initial_2', 'value' => 'BN', 'type' => 'text'],
            ['key' => 'member_name_2', 'value' => 'Bella Nadira', 'type' => 'text'],
            ['key' => 'member_role_2', 'value' => 'Wakil Ketua', 'type' => 'text'],
            ['key' => 'member_code_2', 'value' => 'Giyama/2025/002', 'type' => 'text'],
            ['key' => 'member_desc_2', 'value' => 'Spesialis UI/UX Design dengan portfolio lebih dari 20 proyek desain untuk startup dan perusahaan lokal.', 'type' => 'textarea'],

            // Member 3
            ['key' => 'member_initial_3', 'value' => 'CR', 'type' => 'text'],
            ['key' => 'member_name_3', 'value' => 'Candra Rizky', 'type' => 'text'],
            ['key' => 'member_role_3', 'value' => 'Sekretaris', 'type' => 'text'],
            ['key' => 'member_code_3', 'value' => 'Giyama/2025/003', 'type' => 'text'],
            ['key' => 'member_desc_3', 'value' => 'Mengelola administrasi organisasi dan koordinasi event, aktif di kepanitiaan tingkat universitas.', 'type' => 'textarea'],

            // Member 4
            ['key' => 'member_initial_4', 'value' => 'DP', 'type' => 'text'],
            ['key' => 'member_name_4', 'value' => 'Dian Permata', 'type' => 'text'],
            ['key' => 'member_role_4', 'value' => 'Bendahara', 'type' => 'text'],
            ['key' => 'member_code_4', 'value' => 'Giyama/2025/004', 'type' => 'text'],
            ['key' => 'member_desc_4', 'value' => 'Ahli keuangan yang mengatur anggaran dan sponsorship, berpengalaman di organisasi kemahasiswaan.', 'type' => 'textarea'],

            // Member 5
            ['key' => 'member_initial_5', 'value' => 'EH', 'type' => 'text'],
            ['key' => 'member_name_5', 'value' => 'Eka Hidayat', 'type' => 'text'],
            ['key' => 'member_role_5', 'value' => 'K Divisi Teknologi', 'type' => 'text'],
            ['key' => 'member_code_5', 'value' => 'Giyama/2025/005', 'type' => 'text'],
            ['key' => 'member_desc_5', 'value' => 'Full-stack developer dengan keahlian Laravel, React, dan Flutter. Juara 2 Hackathon Nasional 2025.', 'type' => 'textarea'],

            // Member 6
            ['key' => 'member_initial_6', 'value' => 'FA', 'type' => 'text'],
            ['key' => 'member_name_6', 'value' => 'Fitri Aulia', 'type' => 'text'],
            ['key' => 'member_role_6', 'value' => 'K Divisi Desain', 'type' => 'text'],
            ['key' => 'member_code_6', 'value' => 'Giyama/2025/006', 'type' => 'text'],
            ['key' => 'member_desc_6', 'value' => 'Desainer grafis profesional, berpengalaman membuat branding dan konten visual untuk berbagai komunitas.', 'type' => 'textarea'],

            // Member 7
            ['key' => 'member_initial_7', 'value' => 'GH', 'type' => 'text'],
            ['key' => 'member_name_7', 'value' => 'Galih Prasetyo', 'type' => 'text'],
            ['key' => 'member_role_7', 'value' => 'K Divisi Humas', 'type' => 'text'],
            ['key' => 'member_code_7', 'value' => 'Giyama/2025/007', 'type' => 'text'],
            ['key' => 'member_desc_7', 'value' => 'Menangani hubungan masyarakat, media sosial, dan kerjasama eksternal dengan berbagai stakeholder.', 'type' => 'textarea'],

            // Member 8
            ['key' => 'member_initial_8', 'value' => 'HN', 'type' => 'text'],
            ['key' => 'member_name_8', 'value' => 'Hana Nurhaliza', 'type' => 'text'],
            ['key' => 'member_role_8', 'value' => 'Staf Ahli', 'type' => 'text'],
            ['key' => 'member_code_8', 'value' => 'Giyama/2025/008', 'type' => 'text'],
            ['key' => 'member_desc_8', 'value' => 'Mahasiswa Ilmu Komputer yang aktif mengajar programming dasar untuk anggota baru setiap semester.', 'type' => 'textarea'],

            ['key' => 'achievement_section_title', 'value' => 'Prestasi Kami', 'type' => 'text'],
            ['key' => 'achievement_section_subtitle', 'value' => 'Bukti dedikasi dan kerja keras organisasi dalam mencapai berbagai penghargaan.', 'type' => 'text'],

            // Achievement 1
            ['key' => 'achievement_1_icon', 'value' => '🏆', 'type' => 'text'],
            ['key' => 'achievement_1_title', 'value' => 'Juara 1 - Nasional Innovation Challenge 2025', 'type' => 'text'],
            ['key' => 'achievement_1_year', 'value' => '2025', 'type' => 'text'],
            ['key' => 'achievement_1_desc', 'value' => 'Kompetisi inovasi teknologi tingkat nasional. Tim SIGMA membawa aplikasi manajemen sampah berbasis AI.', 'type' => 'textarea'],

            // Achievement 2
            ['key' => 'achievement_2_icon', 'value' => '🥈', 'type' => 'text'],
            ['key' => 'achievement_2_title', 'value' => 'Juara 2 - Hackathon Indonesia Digital 2025', 'type' => 'text'],
            ['key' => 'achievement_2_year', 'value' => '2025', 'type' => 'text'],
            ['key' => 'achievement_2_desc', 'value' => 'Hackathon 48 jam dengan tema "Digital Solutions for Education". Platform e-learning interaktif.', 'type' => 'textarea'],

            // Achievement 3
            ['key' => 'achievement_3_icon', 'value' => '🥉', 'type' => 'text'],
            ['key' => 'achievement_3_title', 'value' => 'Juara 3 - Lomba Cipta Aplikasi 2024', 'type' => 'text'],
            ['key' => 'achievement_3_year', 'value' => '2024', 'type' => 'text'],
            ['key' => 'achievement_3_desc', 'value' => 'Lomba pembuatan aplikasi tingkat provinsi diikuti 50+ tim dari universitas ternama.', 'type' => 'textarea'],

            // Achievement 4
            ['key' => 'achievement_4_icon', 'value' => '🎖️', 'type' => 'text'],
            ['key' => 'achievement_4_title', 'value' => 'Best Innovation Award - Tech Fest 2025', 'type' => 'text'],
            ['key' => 'achievement_4_year', 'value' => '2025', 'type' => 'text'],
            ['key' => 'achievement_4_desc', 'value' => 'Penghargaan inovasi terbaik dalam acara festival teknologi.', 'type' => 'textarea'],

            // Achievement 5
            ['key' => 'achievement_5_icon', 'value' => '🌟', 'type' => 'text'],
            ['key' => 'achievement_5_title', 'value' => '1000 Startup Digital - Top 100', 'type' => 'text'],
            ['key' => 'achievement_5_year', 'value' => '2024', 'type' => 'text'],
            ['key' => 'achievement_5_desc', 'value' => 'Berhasil masuk 100 besar program pencetakan startup digital Kominfo Indonesia.', 'type' => 'textarea'],

            // Achievement 6
            ['key' => 'achievement_6_icon', 'value' => '📜', 'type' => 'text'],
            ['key' => 'achievement_6_title', 'value' => 'Sertifikasi Internasional - Google IT Support', 'type' => 'text'],
            ['key' => 'achievement_6_year', 'value' => '2025', 'type' => 'text'],
            ['key' => 'achievement_6_desc', 'value' => '15 anggota berhasil mendapatkan sertifikasi Google IT Support.', 'type' => 'textarea'],

            // Achievement 7
            ['key' => 'achievement_7_icon', 'value' => '🚀', 'type' => 'text'],
            ['key' => 'achievement_7_title', 'value' => 'Most Active Community - Developer Circle', 'type' => 'text'],
            ['key' => 'achievement_7_year', 'value' => '2024', 'type' => 'text'],
            ['key' => 'achievement_7_desc', 'value' => 'Diakui sebagai komunitas teknologi paling aktif oleh Facebook Developer Circle.', 'type' => 'textarea'],

            // Achievement 8
            ['key' => 'achievement_8_icon', 'value' => '💡', 'type' => 'text'],
            ['key' => 'achievement_8_title', 'value' => 'Best Paper - Seminar Nasional Informatika', 'type' => 'text'],
            ['key' => 'achievement_8_year', 'value' => '2024', 'type' => 'text'],
            ['key' => 'achievement_8_desc', 'value' => 'Publikasi jurnal ilmiah tentang machine learning klasifikasi penyakit tanaman.', 'type' => 'textarea'],
        ];

        foreach ($defaults as $item) {
            if (!LandingContent::where('key', $item['key'])->exists()) {
                LandingContent::create($item);
            }
        }
    }
}
