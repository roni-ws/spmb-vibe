# Perencanaan Unit Testing: SPMB SMA Unggul Bangsa

Dokumen ini berisi panduan tingkat tinggi untuk mengimplementasikan *unit test* pada *project* SPMB berbasis CodeIgniter 4. Silakan implementasikan skenario-skenario berikut ini.

## Instruksi Umum
- Buatkan unit tests untuk project yang tersedia.
- Simpan unit tests di folder `tests`.
- Setiap skenario, hapus datanya terlebih dahulu (misalnya menggunakan *database transactions* atau metode *refresh database*) agar pengujian konsisten.
- Buat skenario test selengkap mungkin berdasarkan panduan di bawah, namun detail implementasi (*assertions*, pembuatan data *dummy/mocking*) diserahkan sepenuhnya kepada implementator.

---

## Daftar Skenario Pengujian

### 1. Pengujian Model (Database & Relasi)
- **UserModel**: 
  - Skenario: Berhasil menyimpan pengguna baru.
  - Skenario: Gagal menyimpan pengguna jika email/username duplikat.
- **BiodataModel**:
  - Skenario: Berhasil menyimpan biodata siswa yang terhubung dengan valid `user_id`.
  - Skenario: Menghapus pengguna dari `users` akan otomatis menghapus datanya di `biodata` (*Cascade Delete*).
- **SekolahModel & BerkasModel**:
  - Skenario: Berhasil menyimpan data asal sekolah dan berkas pendaftaran.
  - Skenario: Mengambil data berkas berdasarkan `user_id` mengembalikan data yang benar.

### 2. Pengujian Filter / Middleware (Otorisasi Akses)
- **Session Filter**:
  - Skenario: Memblokir akses ke rute *protected* (seperti `/siswa/dashboard`) jika pengunjung tidak memiliki *session* / belum login.
- **Group Filter (Shield)**:
  - Skenario: Memblokir pengguna dengan *role* Siswa yang mencoba mengakses rute milik grup Admin (`/admin/dashboard` & `/admin/verify`).

### 3. Pengujian Controller & Endpoint Web (Autentikasi & Dashboard)
- **Auth (Login & Logout)**:
  - Skenario: Proses Login berhasil menggunakan kredensial Admin.
  - Skenario: Proses Login berhasil menggunakan kredensial Calon Siswa.
  - Skenario: Proses Login gagal (kredensial salah, tidak lengkap, atau format email salah).
  - Skenario: Proses Logout berhasil menghancurkan *session*.
- **Dashboard Calon Siswa (`/siswa/dashboard`)**:
  - Skenario: Akses oleh tamu (*guest*) akan ditolak dan dialihkan (redirect).
  - Skenario: Akses oleh pengguna Admin akan dialihkan ke dashboard admin.
  - Skenario: Akses oleh Calon Siswa berhasil memuat halaman dan persentase kelengkapan berkas yang dinamis.
- **Dashboard Admin (`/admin/dashboard`)**:
  - Skenario: Akses oleh tamu (*guest*) akan ditolak dan dialihkan (redirect).
  - Skenario: Akses oleh Calon Siswa ditolak/dialihkan.
  - Skenario: Akses oleh Admin berhasil memuat statistik jumlah data pendaftar.
- **Verifikasi Berkas Admin (`/admin/verify/(:num)`) POST**:
  - Skenario: Permintaan POST oleh selain Admin ditolak (Unauthorized / Redirect).
  - Skenario: Permintaan POST oleh Admin berhasil memperbarui status pendaftar menjadi "Valid".
  - Skenario: Permintaan POST oleh Admin berhasil memperbarui status pendaftar menjadi "Revisi" beserta penyimpanan "Catatan Verifikasi" terkait.

---
*Catatan untuk Implementator: Fokus pada implementasi kode pengujian. Jangan lupakan pembersihan state database (tearDown / setup) untuk setiap test suite.*
