/**
 * public/js/siswa/orang_tua.js
 * Client-side validation for the Data Orang Tua form.
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const namaAyah = document.getElementById('nama_ayah');
    const pekerjaanAyah = document.getElementById('pekerjaan_ayah');
    const namaIbu = document.getElementById('nama_ibu');
    const pekerjaanIbu = document.getElementById('pekerjaan_ibu');

    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            if (namaAyah && namaAyah.value.trim().length < 3) {
                isValid = false;
                errorMessage += 'Nama lengkap Ayah minimal 3 karakter.\n';
                namaAyah.classList.add('border-error');
            } else if (namaAyah) {
                namaAyah.classList.remove('border-error');
            }

            if (pekerjaanAyah && pekerjaanAyah.value.trim().length < 2) {
                isValid = false;
                errorMessage += 'Pekerjaan Ayah minimal 2 karakter.\n';
                pekerjaanAyah.classList.add('border-error');
            } else if (pekerjaanAyah) {
                pekerjaanAyah.classList.remove('border-error');
            }

            if (namaIbu && namaIbu.value.trim().length < 3) {
                isValid = false;
                errorMessage += 'Nama lengkap Ibu minimal 3 karakter.\n';
                namaIbu.classList.add('border-error');
            } else if (namaIbu) {
                namaIbu.classList.remove('border-error');
            }

            if (pekerjaanIbu && pekerjaanIbu.value.trim().length < 2) {
                isValid = false;
                errorMessage += 'Pekerjaan Ibu minimal 2 karakter.\n';
                pekerjaanIbu.classList.add('border-error');
            } else if (pekerjaanIbu) {
                pekerjaanIbu.classList.remove('border-error');
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
            }
        });
    }
});
