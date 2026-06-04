/**
 * public/js/siswa/sekolah.js
 * Client-side validation for the Asal Sekolah form.
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const namaSekolah = document.getElementById('nama_sekolah');
    const alamatSekolah = document.getElementById('alamat_sekolah');

    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            if (namaSekolah && namaSekolah.value.trim().length < 5) {
                isValid = false;
                errorMessage += 'Nama sekolah asal minimal 5 karakter.\n';
                namaSekolah.classList.add('border-error');
            } else if (namaSekolah) {
                namaSekolah.classList.remove('border-error');
            }

            if (alamatSekolah && alamatSekolah.value.trim().length < 10) {
                isValid = false;
                errorMessage += 'Alamat sekolah asal minimal 10 karakter.\n';
                alamatSekolah.classList.add('border-error');
            } else if (alamatSekolah) {
                alamatSekolah.classList.remove('border-error');
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
            }
        });
    }
});
