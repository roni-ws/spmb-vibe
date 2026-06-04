/**
 * public/js/siswa/berkas.js
 * Client-side validation for the upload files form.
 * Ensures selected files are under 2MB and have correct extensions (PDF, JPG, JPEG, PNG).
 */
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = [
        { id: 'fc_ijazah_skl', label: 'Scan Ijazah / SKL' },
        { id: 'fc_kk', label: 'Scan Kartu Keluarga' },
        { id: 'pas_foto', label: 'Pas Foto 3x4' }
    ];

    const maxSizeBytes = 2 * 1024 * 1024; // 2MB
    const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

    fileInputs.forEach(fileInputConfig => {
        const input = document.getElementById(fileInputConfig.id);
        if (input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Check file size
                    if (file.size > maxSizeBytes) {
                        alert(`File "${fileInputConfig.label}" melebihi batas ukuran maksimal 2MB. Silakan kompres atau pilih berkas lain.`);
                        this.value = ''; // Reset input
                        return;
                    }

                    // Check file extension
                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(extension)) {
                        alert(`Format berkas "${fileInputConfig.label}" tidak didukung. Harap unggah berkas dengan format: PDF, JPG, JPEG, atau PNG.`);
                        this.value = ''; // Reset input
                        return;
                    }
                }
            });
        }
    });

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Additional checks on submit if required
            let allValid = true;
            fileInputs.forEach(fileInputConfig => {
                const input = document.getElementById(fileInputConfig.id);
                if (input && input.hasAttribute('required') && !input.files[0] && !input.getAttribute('value')) {
                    // Check if it's already uploaded on backend (verified via "Sudah Terunggah" label, check if input has files or value)
                    // If it is required and has no files, let the browser handle standard required validation
                }
            });
        });
    }
});
