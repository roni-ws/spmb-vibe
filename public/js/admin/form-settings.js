/**
 * public/js/admin/settings.js
 * JavaScript for the Admin "Pengaturan Website" page.
 * Handles tab switching, image previews, announcement modals, and Quill Editor synchronization.
 */

/**
 * Switch Sub-Tab without reloading the page
 * @param {string} tabId Target tab ID to display
 */
function switchSubTab(tabId) {
    // Hide all panes
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
    
    // Show target pane
    document.getElementById(tabId).classList.remove('hidden');
    
    // Deactivate all tab buttons styling
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-primary', 'text-primary', 'bg-surface-container-high');
        btn.classList.add('text-on-surface-variant', 'border-transparent', 'hover:text-on-surface', 'hover:bg-surface-container');
    });
    
    // Activate target button styling
    const activeBtn = document.getElementById('btn-' + tabId);
    if (activeBtn) {
        activeBtn.classList.add('active', 'border-primary', 'text-primary');
        activeBtn.classList.remove('text-on-surface-variant', 'border-transparent', 'hover:text-on-surface', 'hover:bg-surface-container');
    }
}

/**
 * Preview uploaded images in the file browser
 */
function previewImage(input, previewId, placeholderId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.classList.remove('hidden');
            
            const placeholder = document.getElementById(placeholderId);
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        }
        reader.readAsDataURL(file);
    }
}

// =========================================================================
// MODAL HANDLERS FOR ANNOUNCEMENTS
// =========================================================================
function openAnnouncementModal() {
    const modal = document.getElementById('modal-announcement');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('opacity-100');
        modal.querySelector('.bg-surface').classList.remove('scale-95');
        modal.querySelector('.bg-surface').classList.add('scale-100');
    }, 10);
}

function closeAnnouncementModal() {
    const modal = document.getElementById('modal-announcement');
    modal.classList.remove('opacity-100');
    modal.querySelector('.bg-surface').classList.add('scale-95');
    modal.querySelector('.bg-surface').classList.remove('scale-100');
    setTimeout(() => {
        modal.classList.add('hidden');
        // Reset input values
        document.getElementById('judul').value = '';
        document.getElementById('konten').value = '';
    }, 300);
}

function confirmDeleteAnnouncement(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengumuman pendaftaran ini secara permanen? Calon siswa tidak akan melihat pengumuman ini lagi.')) {
        fetch(BASE_URL + 'admin/settings/announcement/delete/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ [CSRF_TOKEN]: CSRF_HASH })
        }).then(function (r) { return r.json(); })
          .then(function (data) {
              location.reload();
          });
    }
}

// Set initial active tab styling & parse tab from URL
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    if (activeTab && document.getElementById('tab-' + activeTab)) {
        switchSubTab('tab-' + activeTab);
    } else {
        switchSubTab('tab-branding');
    }

    // Init Quill editor for Kop Surat
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'header': [1, 2, 3, false] }],
                [{ 'align': [] }],
                ['link', 'clean']
            ]
        }
    });

    // Sync Quill content to hidden textarea on form submit
    document.querySelector('#settings-form').onsubmit = function() {
        document.querySelector('#kop-content-hidden').value = quill.root.innerHTML;
    };
});
