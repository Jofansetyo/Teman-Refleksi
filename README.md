# Teman Refleksi (Laravel 11)

Skrining stres mandiri (PSS-10). Anonim, bukan diagnosis medis.
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
- `PssScorer` menghitung skor di server (item 4, 5, 7, 8 dibalik). Browser tidak bisa memanipulasi skor.
- Tabel: `questions`, `submissions` (UUID acak, tanpa nama/kontak/IP), `answers`.
- Persetujuan (consent) wajib sebelum tes dan disimpan per pengisian.
- Pengiriman jawaban dibatasi 10 kali per menit per perangkat.

## Langkah berikutnya
1. Pertanyaan gaya hidup (tipe `gaya_hidup`) dan pertanyaan keselamatan.
2. Admin Filament dengan `->topNavigation()`, ringkasan, dan ekspor CSV.
3. Hosting dengan HTTPS.
4. API Python (FastAPI) + `model.joblib` setelah data asli terkumpul.
