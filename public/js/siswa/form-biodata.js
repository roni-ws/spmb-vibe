/**
 * public/js/siswa/biodata.js
 * Client-side validation and input filtering for the Biodata form.
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nikInput = document.getElementById('nik');
    const kkInput = document.getElementById('no_kk');
    const hpInput = document.getElementById('no_hp');

    // Filter non-digit characters on input
    const filterDigits = (input) => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    };

    if (nikInput) filterDigits(nikInput);
    if (kkInput) filterDigits(kkInput);
    if (hpInput) filterDigits(hpInput);

    // Form submission validation
    if (form) {
        form.addEventListener('submit', function(e) {
            var errors = [];

            if (nikInput && nikInput.value.length !== 16) {
                errors.push('NIK harus tepat 16 digit.');
                nikInput.classList.add('border-error');
            } else if (nikInput) {
                nikInput.classList.remove('border-error');
            }

            if (kkInput && kkInput.value.length !== 16) {
                errors.push('Nomor Kartu Keluarga harus tepat 16 digit.');
                kkInput.classList.add('border-error');
            } else if (kkInput) {
                kkInput.classList.remove('border-error');
            }

            if (hpInput && (hpInput.value.length < 10 || hpInput.value.length > 15)) {
                errors.push('Nomor HP harus berkisar antara 10 hingga 15 digit.');
                hpInput.classList.add('border-error');
            } else if (hpInput) {
                hpInput.classList.remove('border-error');
            }

            if (errors.length) {
                e.preventDefault();
                showFormAlert(form, errors);
            }
        });
    }
});
