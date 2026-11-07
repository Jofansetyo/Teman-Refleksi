# Teman Refleksi (Laravel 11)

Refleksi stres mandiri. Anonim dan bukan diagnosis medis atau instrumen PSS-10 tervalidasi.
Folder ini adalah **lapisan file proyek** yang ditimpa ke Laravel 11 baru.

## Cara pasang
```bash
composer create-project laravel/laravel:^11.0 teman-refleksi   # PHP 8.2+
# salin isi folder ini ke dalam teman-refleksi/ (timpa file yang sama)
cd teman-refleksi
npm install && npm install alpinejs
# edit .env: DB_CONNECTION=mysql, DB_DATABASE=teman_refleksi, dst
php artisan migrate --seed
php artisan serve        # terminal 1
npm run dev              # terminal 2
```
Buka http://127.0.0.1:8000

## Isi
- `PssScorer` menghitung skor refleksi di server (item 4, 5, 7, 8 dibalik); browser tidak mengirim skor.
- Tabel: `questions`, `submissions` (UUID acak, tanpa nama, kontak, atau IP), dan `answers`.
- Persetujuan wajib sebelum tes; token sesi sekali pakai mencegah pengiriman langsung yang melewati halaman persetujuan.
- Form memakai honeypot dan pembatasan 5 pengiriman per menit per alamat IP.

## Privasi dan retensi data

- Data yang disimpan hanya jawaban, skor, kategori, waktu pengisian, dan UUID acak; jangan tambahkan data identitas atau alamat IP ke aplikasi maupun log.
- Data refleksi dihapus otomatis setelah 30 hari melalui scheduler. Aktifkan scheduler produksi dengan `php artisan schedule:work` atau satu cron job yang menjalankan `php artisan schedule:run` setiap menit.
- Sebelum produksi, publikasikan kebijakan privasi yang memuat tujuan pemrosesan, dasar persetujuan, masa retensi 30 hari, pihak yang dapat mengakses data, dan kanal untuk pertanyaan atau permintaan penghapusan.
- Instrumen ini bukan PSS-10 tervalidasi. Untuk penelitian atau keputusan klinis, gunakan versi Bahasa Indonesia yang tervalidasi dan pastikan izin penggunaan yang diperlukan.

## Langkah berikutnya
1. Pertanyaan gaya hidup (tipe `gaya_hidup`) dan pertanyaan keselamatan.
2. Admin Filament dengan `->topNavigation()`, ringkasan, dan ekspor CSV.
3. Hosting dengan HTTPS.
4. API Python (FastAPI) + `model.joblib` setelah data asli terkumpul.
