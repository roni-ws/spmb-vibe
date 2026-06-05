(function () {
    'use strict';

    function openAnnouncementModal() {
        var modal = document.getElementById('modal-announcement');
        modal.classList.remove('hidden');
        setTimeout(function () {
            modal.classList.add('opacity-100');
            modal.querySelector('.bg-surface').classList.remove('scale-95');
            modal.querySelector('.bg-surface').classList.add('scale-100');
        }, 10);
    }

    function closeAnnouncementModal() {
        var modal = document.getElementById('modal-announcement');
        modal.classList.remove('opacity-100');
        modal.querySelector('.bg-surface').classList.add('scale-95');
        modal.querySelector('.bg-surface').classList.remove('scale-100');
        setTimeout(function () {
            modal.classList.add('hidden');
            document.getElementById('judul').value = '';
            document.getElementById('konten').value = '';
        }, 300);
    }

    window.openAnnouncementModal = openAnnouncementModal;
    window.closeAnnouncementModal = closeAnnouncementModal;

    window.confirmDeleteAnnouncement = function (id) {
        if (confirm('Apakah Anda yakin ingin menghapus pengumuman pendaftaran ini secara permanen? Calon siswa tidak akan melihat pengumuman ini lagi.')) {
            fetch(BASE_URL + 'admin/pengumuman/delete/' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams({ [CSRF_TOKEN]: CSRF_HASH })
            }).then(function (r) { return r.json(); })
              .then(function () {
                  location.reload();
              });
        }
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('modal-announcement');
            if (modal && !modal.classList.contains('hidden')) {
                closeAnnouncementModal();
            }
        }
    });
})();
