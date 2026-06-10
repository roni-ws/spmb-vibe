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

    // Inisialisasi TomSelect untuk Autocomplete Sekolah
    if (namaSekolah) {
        new TomSelect("#nama_sekolah", {
            valueField: 'value',
            labelField: 'text',
            searchField: 'text',
            create: true,
            maxItems: 1,
            placeholder: "Ketik nama sekolah Anda...",
            load: function(query, callback) {
                if (query.length < 2) return callback();
                
                fetch('/api/sekolah/search?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(json => {
                        callback(json);
                    })
                    .catch(() => {
                        callback();
                    });
            },
            render: {
                option_create: function(data, escape) {
                    return '<div class="create p-2 cursor-pointer bg-primary/10 text-primary font-semibold rounded-md mx-1 my-1 hover:bg-primary hover:text-white transition-colors">Tambahkan "<strong>' + escape(data.input) + '</strong>" ke dalam sistem</div>';
                },
                no_results: function(data, escape) {
                    return '<div class="no-results p-2 text-on-surface-variant italic">Sekolah tidak ditemukan. Tekan "Enter" untuk menambahkannya.</div>';
                },
                option: function(data, escape) {
                    return '<div class="p-2 cursor-pointer hover:bg-surface-variant">' + escape(data.text) + '</div>';
                }
            }
        });
    }
});
