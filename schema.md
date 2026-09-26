Saya memiliki sebuah proyek Laravel 11 dengan konfigurasi berikut:

* Laravel Framework: ^11.0
* PHP: ^8.2
* Database: SQLite
* Node.js dan npm digunakan untuk frontend/Vite
* Composer digunakan untuk dependency PHP
* Tidak ingin menggunakan Docker
* Tidak ingin menginstall MySQL atau PostgreSQL
* Tidak ingin menggunakan XAMPP
* Gunakan SQLite sebagai database lokal
* Tujuan utama: menjalankan proyek Laravel secara lokal untuk development/testing

Dependency utama proyek:

* maatwebsite/excel
* simplesoftwareio/simple-qrcode
* laravel/tinker

Environment `.env` menggunakan:

DB_CONNECTION=sqlite
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
APP_MAINTENANCE_STORE=database

Saya ingin kamu membantu melakukan setup proyek ini secara STEP-BY-STEP.

ATURAN PENTING:

1. Jangan menjalankan semua perintah sekaligus.
2. Kerjakan hanya SATU tahap pada satu waktu.
3. Setelah menjalankan sebuah perintah, PERIKSA OUTPUT-nya.
4. Jangan lanjut ke tahap berikutnya sebelum tahap sebelumnya berhasil.
5. Jika terjadi error, berhenti dan analisis error tersebut terlebih dahulu.
6. Jangan menebak penyebab error. Gunakan output terminal sebagai dasar diagnosis.
7. Jangan menginstall software yang tidak diperlukan.
8. Jangan menginstall MySQL, PostgreSQL, XAMPP, Apache, Nginx, Redis, Memcached, atau Docker kecuali benar-benar terbukti diperlukan.
9. Gunakan PHP built-in development server untuk menjalankan Laravel.
10. Gunakan SQLite untuk database.
11. Jangan mengubah source code aplikasi kecuali memang diperlukan untuk memperbaiki masalah setup.
12. Jangan mengubah struktur database/migration tanpa alasan yang jelas.
13. Jangan menghapus file proyek.
14. Jangan mengganti dependency proyek tanpa alasan.
15. Sebelum melakukan perubahan konfigurasi, jelaskan terlebih dahulu apa yang akan diubah dan mengapa.
16. Setelah setiap tahap berhasil, tampilkan:

* STATUS: BERHASIL
* OUTPUT yang menjadi bukti keberhasilan
* Tahap berikutnya yang akan dilakukan

TAHAP 1 — PERIKSA ENVIRONMENT

Pertama, jangan install apa pun.

Periksa terlebih dahulu:

php -v
composer --version
node -v
npm -v

Periksa juga apakah SQLite tersedia:

php -m | grep -i sqlite

Tujuan tahap ini adalah mengetahui software apa yang sudah tersedia di komputer.

Setelah perintah selesai:

* Analisis versinya.
* Pastikan PHP minimal 8.2.
* Pastikan Composer tersedia.
* Pastikan Node.js dan npm tersedia.
* Pastikan PHP memiliki extension SQLite/PDO SQLite.

Jika PHP belum tersedia atau versinya di bawah 8.2:

* BERHENTI.
* Jelaskan bahwa PHP perlu dipasang/diperbarui.
* Berikan instruksi instalasi paling ringan sesuai OS komputer.
* Jangan memasang software lain.

Jika Composer belum tersedia:

* BERHENTI.
* Jelaskan bahwa Composer perlu dipasang.
* Jangan lanjut ke instalasi dependency.

Jika SQLite extension belum tersedia:

* BERHENTI.
* Jelaskan cara mengaktifkan/memasang SQLite extension untuk PHP sesuai OS.
* Jangan install database server.

TAHAP 2 — PERIKSA STRUKTUR PROYEK

Jika environment memenuhi syarat, periksa:

ls
ls -la

Pastikan file penting seperti berikut tersedia:

composer.json
.env.example
artisan
package.json

Periksa juga:

ls -la database

Jangan mengubah file apa pun pada tahap ini.

TAHAP 3 — SIAPKAN `.env`

Jika `.env` belum ada, buat dari `.env.example`.

Jika `.env` sudah ada, jangan menimpanya.

Pastikan konfigurasi database:

DB_CONNECTION=sqlite

Periksa apakah file database SQLite sudah ada:

database/database.sqlite

Jika belum ada, buat:

touch database/database.sqlite

Untuk Windows gunakan perintah yang sesuai dengan shell yang sedang digunakan.

Jangan menggunakan MySQL atau PostgreSQL.

TAHAP 4 — INSTALL DEPENDENCY PHP

Jalankan:

composer install

Setelah selesai, periksa hasilnya.

Jika berhasil, pastikan folder berikut tersedia:

vendor/

Kemudian jalankan:

php artisan --version

Pastikan Laravel berhasil dikenali.

Jika `composer install` menghasilkan error:

* BERHENTI.
* Jangan mencoba berbagai solusi secara acak.
* Analisis pesan error.
* Periksa versi PHP dan extension yang dibutuhkan.
* Tampilkan solusi berdasarkan error yang sebenarnya.

TAHAP 5 — GENERATE APPLICATION KEY

Jika `.env` belum memiliki APP_KEY, jalankan:

php artisan key:generate

Kemudian verifikasi bahwa APP_KEY sudah terisi di `.env`.

Jangan menampilkan nilai APP_KEY secara lengkap dalam output.

TAHAP 6 — DATABASE SQLITE

Pastikan:

database/database.sqlite

tersedia.

Kemudian jalankan:

php artisan migrate

Jika migration berhasil, tampilkan status migration.

Jika gagal:

* BERHENTI.
* Jangan menghapus database.
* Analisis error migration.
* Periksa apakah migration menggunakan fitur yang kompatibel dengan SQLite.

TAHAP 7 — INSTALL FRONTEND DEPENDENCY

Setelah Laravel dan database berhasil, periksa:

package.json

Kemudian jalankan:

npm install

Jika berhasil, pastikan `node_modules` tersedia.

Jangan melakukan `npm audit fix --force` secara otomatis.

Jika ada warning vulnerability, cukup tampilkan informasinya dan jangan mengubah dependency kecuali diperlukan.

TAHAP 8 — BUILD/RUN FRONTEND

Periksa scripts di `package.json`.

Jika tersedia script `dev`, jalankan:

npm run dev

Jika tersedia script `build`, lakukan build setelah development environment berhasil.

Jangan menjalankan `npm run dev` dan `php artisan serve` dalam satu terminal jika keduanya membutuhkan terminal aktif.

TAHAP 9 — JALANKAN LARAVEL

Gunakan Laravel development server:

php artisan serve

Expected result kira-kira:

Server running on [http://127.0.0.1:8000]

Kemudian akses melalui browser.

Jika Vite membutuhkan server development, gunakan terminal kedua:

npm run dev

Jelaskan URL Laravel dan URL Vite berdasarkan OUTPUT SEBENARNYA, jangan menebak port.

TAHAP 10 — TEST APLIKASI

Setelah server berjalan, lakukan pengecekan:

php artisan about

Kemudian:

php artisan route:list

Pastikan Laravel dapat membaca konfigurasi dan route.

Jika proyek memiliki test:

php artisan test

Jalankan test dan tampilkan hasilnya.

Jangan memperbaiki test yang gagal secara otomatis tanpa menjelaskan penyebabnya terlebih dahulu.

TAHAP 11 — HASIL AKHIR

Jika semuanya berhasil, berikan ringkasan:

Environment:

* PHP:
* Composer:
* Node.js:
* npm:
* SQLite:

Laravel:

* Laravel version:
* APP_ENV:
* Database:
* Migration:

Frontend:

* npm install:
* Vite:

Server:

* Laravel URL:
* Vite URL:

Kemudian berikan perintah untuk menjalankan proyek di kemudian hari.

Contoh:

Terminal 1:
php artisan serve

Terminal 2:
npm run dev

PENTING:

Saya ingin prosesnya seperti troubleshooting interaktif.

Format setiap tahap:

================================
TAHAP X — NAMA TAHAP
====================

Perintah:
[command]

Kemudian tunggu OUTPUT.

Setelah output tersedia:
STATUS:
BERHASIL / GAGAL

Analisis:
[analisis berdasarkan output]

Jika BERHASIL:
Lanjutkan ke tahap berikutnya.

Jika GAGAL:
STOP dan perbaiki tahap tersebut terlebih dahulu.

JANGAN melanjutkan ke tahap berikutnya sebelum tahap saat ini benar-benar berhasil.
